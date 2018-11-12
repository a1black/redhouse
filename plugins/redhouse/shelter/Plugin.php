<?php

declare(strict_types=1);

namespace Redhouse\Shelter;

use App;
use Lang;
use Backend;
use Controller;
use System\Classes\PluginBase;
use Redhouse\Shelter\Classes\ListTypeHelpers;
use Redhouse\Shelter\Classes\TwigExtensions;

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
        return [
            'Redhouse\Shelter\Components\Animal' => 'animalView',
            'Redhouse\Shelter\Components\Animals' => 'animalCatalog',
            'Redhouse\Shelter\Components\AnimalAlert' => 'sickAnimals',
            'Redhouse\Shelter\Components\Contacts' => 'contactList',
            'Redhouse\Shelter\Components\MoneyAccounts' => 'moneyAccounts',
            'Redhouse\Shelter\Components\SocialLikes' => 'socialLikes',
            'Redhouse\Shelter\Components\SocialLinks' => 'socialLinks',
            'Redhouse\Shelter\Components\YandexMap' => 'yandexmap',
            'Redhouse\Shelter\Components\FinanceHistory' => 'finances',
        ];
    }

    public function registerMarkupTags(): array
    {
        return [
            'filters' => [
                'urlencode' => 'urlencode',
                'strlimit' => 'str_limit',
                'ftrim' => function ($str, $charlist = " \t\n\r\0\x0B") {
                    return trim(mb_ereg_replace('/\s{2,}/', ' '), $charlist);
                },
                'uppercase' => function ($str) {
                    return mb_convert_case($str, MB_CASE_UPPER, "UTF-8");
                },
                'lowercase' => function ($str) {
                    return mb_convert_case($str, MB_CASE_LOWER, "UTF-8");
                },
                'ucfirst' => function ($str) {
                    $str = mb_convert_case($str, MB_CASE_LOWER, "UTF-8");

                    return mb_convert_case($str[0], MB_CASE_UPPER).substr($str, 1);
                },
                'phone' => ['\Redhouse\Shelter\Classes\TwigExtensions', 'phoneNumber'],
                'monthsToAge' => ['\Redhouse\Shelter\Classes\TwigExtensions', 'age'],
                'verbend' => ['\Redhouse\Shelter\Classes\TwigExtensions', 'verbGender'],
                'postdate' => ['\Redhouse\Shelter\Classes\TwigExtensions', 'postdate'],
            ],
            'functions' => [],
        ];
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
            'sex_type' => function ($value) {
                return Lang::get('redhouse.shelter::lang.animal.sex.'.$value);
            },
            'health_type' => function ($value) {
                return Lang::get('redhouse.shelter::lang.animal.health.'.$value);
            },
            'animal_type' => function ($value) {
                return Lang::get('redhouse.shelter::lang.animal.type.'.$value);
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
            ],
        ];
    }
}
