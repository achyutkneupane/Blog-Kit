<?php

declare(strict_types=1);

namespace App\Filament\Commands\FileGenerators\Resources;

use Filament\Commands\FileGenerators\Resources\ResourceClassGenerator as BaseResourceClassGenerator;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Support\Commands\FileGenerators\Contracts\FileGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;

class ResourceClassGenerator extends BaseResourceClassGenerator
{
    protected function addNavigationIconPropertyToClass(ClassType $class): void
    {
        parent::addNavigationIconPropertyToClass($class);

        $navigationIcon = $class->getProperty('navigationIcon');
        $property = $navigationIcon->setValue(new Literal('Heroicon::RectangleStack'));

        $this->configureNavigationIconProperty($property);

        $property = $class->addProperty('activeNavigationIcon', new Literal('Heroicon::OutlinedRectangleStack'))
            ->setProtected()
            ->setStatic()
            ->setType('string|BackedEnum|null');
        $this->configureNavigationIconProperty($property);
    }

    protected function addPropertiesToClass(ClassType $class): void
    {
        parent::addPropertiesToClass($class);

        if ($this->isSimple()) {
            return;
        }

        $this->namespace->addUse(SubNavigationPosition::class);

        $class->addProperty('subNavigationPosition', new Literal('SubNavigationPosition::Top'))
            ->setProtected()
            ->setStatic()
            ->setType(SubNavigationPosition::class.'|null');
    }

    protected function addMethodsToClass(ClassType $class): void
    {
        parent::addMethodsToClass($class);

        $this->generateSEOPage();

        if ($this->isSimple()) {
            return;
        }

        $this->namespace->addUse(Page::class);

        $viewPage = array_key_exists('view', $this->getPageRoutes()) ? $this->getPageRoutes()['view']['class'] : null;
        $editPage = array_key_exists('edit', $this->getPageRoutes()) ? $this->getPageRoutes()['edit']['class'] : null;

        $this->namespace->addUse($viewPage);
        $this->namespace->addUse($editPage);

        $methodBody = <<<PHP
                return \$page->generateNavigationItems([
                    {$this->simplifyFqn($viewPage)}::class,
                    {$this->simplifyFqn($editPage)}::class,
                ]);
                PHP;

        $method = $class->addMethod('getRecordSubNavigation')
            ->setPublic()
            ->setStatic()
            ->setReturnType('array')
            ->setBody($methodBody);
        $method->addParameter('page')
            ->setType(Page::class);
    }

    protected function writeFile(string $path, string|FileGenerator $contents): void
    {
        $filesystem = app(Filesystem::class);

        $filesystem->ensureDirectoryExists(
            pathinfo($path, PATHINFO_DIRNAME),
        );

        $filesystem->put($path, (($contents instanceof FileGenerator) ? $contents->generate() : $contents));
    }

    private function generateSEOPage(): void
    {
        $modelBasename = class_basename($this->modelFqn);
        $singularModelBasename = Str::singular($modelBasename);
        $namespace = $this->extractNamespace($this->getFqn());
        $directory = str_replace('\\', '/', app()->basePath().'/'.str_replace('App\\', 'app/', $namespace));

        $path = "{$directory}/Pages/Manage{$singularModelBasename}SEO.php";

        $this->writeFile($path, app(ResourceSEOPageClassGenerator::class, [
            'fqn' => "{$namespace}\\Pages\\Manage{$singularModelBasename}SEO",
            'resourceFqn' => $this->fqn,
            'hasViewOperation' => $this->hasViewOperation(),
            'isSoftDeletable' => $this->isSoftDeletable(),
        ]));
    }
}
