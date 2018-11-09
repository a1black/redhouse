<?php

declare(strict_types=1);

namespace Redhouse\LatestNews;

use App;
use Lang;
use Backend;
use Controller;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $require = ['RainLab.Blog'];

    public function pluginDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.plugin.name',
            'description' => 'redhouse.shelter::lang.plugin.description',
            'author' => 'a1black',
            'icon' => 'oc-icon-newspaper-o',
            'homepage' => 'https://github.com/a1black/redhouse',
        ];
    }

    public function registerComponents(): array
    {
        return [
            'Redhouse\LatestNews\Components\LatestNews' => 'latestNews',
        ];
    }
}
