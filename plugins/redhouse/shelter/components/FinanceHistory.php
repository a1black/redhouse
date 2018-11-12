<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

use Storage;
use Illuminate\Filesystem\Filesystem;
use October\Rain\Support\Collection;
use Cms\Classes\ComponentBase;
use Cms\Helpers\File as FileHelpers;

class FinanceHistory extends ComponentBase
{
    /**
     * @var October\Rain\Support\Collection list of files
     */
    public $files;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.component.financehistory.name',
            'description' => 'redhouse.shelter::lang.component.financehistory.description',
        ];
    }

    public function onRun()
    {
        $this->files = $this->page['files'] = $this->loadLinks();
    }

    public function onRender()
    {
        if (empty($this->files)) {
            $this->files = $this->page['files'] = $this->loadLinks();
        }
    }

    /**
     * Returns URLs for downloading finance reports.
     */
    public function loadLinks(): Collection
    {
        $files = new Collection([]);
        $filesystem = new Filesystem();
        $allowedExt = ['doc', 'docx', 'csv', 'xls', 'xlsx'];
        foreach (Storage::files('media/finances') as $file) {
            if (FileHelpers::validateExtension($file, $allowedExt)) {
                $files->push([
                    'name' => $filesystem->name($file),
                    'url' => Storage::url('app/'.$file)
                ]);
            }
        }

        return $files->sortByDesc(function ($value, $key) {
            return $value['name'];
        });
    }
}
