<?php

declare(strict_types=1);

namespace Redhouse\Shelter;

use App;
use Lang;
use Backend;
use Controller;
use System\Classes\PluginBase;
use Redhouse\Shelter\Classes\ListTypeHelpers;

class Plugin extends PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.plugin.name',
            'description' => 'redhouse.shelter::lang.plugin.description',
            'author' => 'a1black',
            'icon' => 'oc-icon-paw',
            'homepage' => 'https://github.com/a1black/redhouse',
        ];
    }

    public function registerComponents(): array
    {
        return [];
    }

    public function registerListColumnTypes(): array
    {
        return [
            'phone_number' => function ($value) {
                return ListTypeHelpers::prettyPhoneNumber($value);
            },
            'phone_type' => function ($value) {
                return Lang::get('redhouse.shelter::lang.contact_number.type.'.$value);
            },
            'cashaccount_type' => function ($value) {
                return Lang::get('redhouse.shelter::lang.cashaccount.type.'.$value);
            },
            'age' => function ($value) {
                return ListTypeHelpers::age($value);
            },
        ];
    }

    public function registerNavigation(): array
    {
        return [
            'shelter' => [
                'label' => 'redhouse.shelter::lang.nav.menu.label',
                'url' => Backend::url('redhouse/shelter/animals'),
                'icon' => 'oc-icon-paw',
                'permissions' => [],
                'order' => 250,

                'sideMenu' => [
                    'animals' => [
                        'label' => 'redhouse.shelter::lang.nav.animals.label',
                        'icon' => 'oc-icon-github-alt',
                        'url' => Backend::url('redhouse/shelter/animals'),
                        'permissions' => [],
                    ],
                    'cashaccounts' => [
                        'label' => 'redhouse.shelter::lang.nav.cashaccounts.label',
                        'icon' => 'oc-icon-bank',
                        'url' => Backend::url('redhouse/shelter/cashaccounts'),
                        'permissions' => [],
                    ],
                    'social' => [
                        'label' => 'redhouse.shelter::lang.nav.social.label',
                        'icon' => 'oc-icon-globe',
                        'url' => Backend::url('redhouse/shelter/sociallinks'),
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
