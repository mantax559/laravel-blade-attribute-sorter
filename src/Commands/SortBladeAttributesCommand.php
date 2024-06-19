<?php

namespace Mantax559\LaravelBladeAttributeSorter\Commands;

use Mantax559\LaravelBladeAttributeSorter\Services\SortAttributesService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SortBladeAttributesCommand extends Command
{
    protected $signature = 'blade:sort-attributes';

    protected $description = 'Sort attributes in Blade files';

    public function handle(SortAttributesService $sortAttributesService): void
    {
        $this->info('Sorting attributes in Blade files...');

        $viewPath = resource_path('views');
        $bladeFiles = File::allFiles($viewPath);

        foreach ($bladeFiles as $bladeFile) {
            if ($bladeFile->getExtension() === 'php') {
                $filePath = $bladeFile->getRealPath();
                $fileContent = File::get($filePath);
                $sortedContent = $sortAttributesService->sortAttributes($fileContent);
                File::put($filePath, $sortedContent);
                $this->info("Formatted: $filePath");
            }
        }

        $this->info('Attribute sorting completed.');
    }
}
