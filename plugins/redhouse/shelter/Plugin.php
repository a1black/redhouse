<?php

declare(strict_types=1);

namespace Redhouse\Shelter;

use App;
use Backend;
use Controller;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'redhouse.shelter::lang.plugin.name',
            'description' => 'redhouse.shelter::lang.plugin.description',
            'author' => 'a1black',
            'icon' => 'oc-icon-paw',
            'homepage' => 'https://github.com/a1black/redhouse',
        ];
    }

    public function registerMarkupTags()
    {
        return [];
    }

    public function registerComponents()
    {
        return [];
    }

    public function registerSettings()
    {
        return [];
    }

    public function registerNavigation()
    {
        return [
            'shelter' => [
                'label' => 'redhouse.shelter::lang.nav.menu.label',
                'url' => Backend::url('redhouse/shelter/animals'),
                'icon' => 'oc-icon-paw',
                'permissions' => [],
                'order' => 250,

                'sideMenu' => [
                    'social' => [
                        'label' => 'redhouse.shelter::lang.nav.social.label',
                        'icon' => 'oc-icon-globe',
                        'url' => Backend::url('redhouse/shelter/social'),
                        'permissions' => [],
                    ],
                    'contacts' => [
                        'label' => 'redhouse.shelter::lang.nav.contacts.label',
                        'icon' => 'oc-icon-phone',
                        'url' => Backend::url('redhouse/shelter/contacts'),
                        'permissions' => [],
                    ],
                    'settings' => [
                        'label' => 'redhouse.shelter::lang.nav.settings.label',
                        'icon' => 'oc-icon-gear',
                        'url' => Backend::url('redhouse/shelter/settings'),
                        'permissions' => [],
                    ],
                ],
            ]
        ];
    }
}
