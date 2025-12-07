<?php

declare(strict_types=1);

namespace App\Filament\Commands\FileGenerators\Resources;

use Filament\Commands\FileGenerators\Resources\ResourceClassGenerator as BaseResourceClassGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;

class ResourceClassGenerator extends BaseResourceClassGenerator
{
    protected function addNavigationIconPropertyToClass(ClassType $class): void
    {
        parent::addNavigationIconPropertyToClass($class);

        $navigationIcon = $class->getProperty('navigationIcon');

        $property = $navigationIcon->setValue(new Literal('Heroicon::RectangleStack'))
            ->setProtected()
            ->setStatic()
            ->setType('string|BackedEnum|null');
        $this->configureNavigationIconProperty($property);

        $property = $class->addProperty('activeNavigationIcon', new Literal('Heroicon::OutlinedRectangleStack'))
            ->setProtected()
            ->setStatic()
            ->setType('string|BackedEnum|null');
        $this->configureNavigationIconProperty($property);
    }
}
