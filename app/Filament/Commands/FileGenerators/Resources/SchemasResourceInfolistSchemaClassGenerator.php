<?php

namespace App\Filament\Commands\FileGenerators\Resources;

use Filament\Commands\FileGenerators\Resources\Schemas\ResourceInfolistSchemaClassGenerator as BaseSchemasResourceInfolistSchemaClassGenerator;
use Filament\Schemas\Components\Section;

class SchemasResourceInfolistSchemaClassGenerator extends BaseSchemasResourceInfolistSchemaClassGenerator
{
    public function generateInfolistMethodBody(?string $model = null, array $exceptColumns = []): string
    {
        return <<<PHP
            return \$schema
                ->components([
                    Section::make()
                        ->columns()
                        ->columnSpanFull()
                        ->components([
                            {$this->outputInfolistComponents($model, $exceptColumns)}
                        ]),
                ]);
            PHP;
    }
}
