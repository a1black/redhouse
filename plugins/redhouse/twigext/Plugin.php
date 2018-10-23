<?php

namespace Redhouse\TwigExt;

use Lang;
use Carbon\Carbon;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'redhouse.twigext::lang.plugin.name',
            'description' => 'redhouse.twigext::lang.plugin.description',
            'author' => 'a1black',
            'icon' => 'cms-icon',
        ];
    }

    public function registerMarkupTags()
    {
        $filters = [];

        // Add PHP based filters
        $filters += $this->getPhpBaseFilters();

        // Add custom filters
        $filters['age'] = [$this, 'makeAge'];

        return [
            'filters' => $filters,
        ];
    }

    /**
     * Return twig filter for converting month to 'X years Y months' string.
     *
     * @return string
     */
    public function makeAge($months)
    {
        $years = floor(int($months) / 12);
        $months = $months % 12;
        $str = [
            $years
                ? Lang::choise(
                    'redhouse.twigext::lang.age.years',
                    $years,
                    array('years' => $years))
                : '',
            $months
                ? Lang::choise(
                    'redhouse.twigext::lang.age.months',
                    $months,
                    array('months' => $months))
                : ''
        ];
        return trim(implode(' ', $str));
    }

    /**
     * Returns array of twig filters based on builtin PHP functions.
     *
     * @return array
     */
    protected function getPhpBaseFilters()
    {
        return [
            'uppercase' => function($str) {
                return mb_convert_case($str, MB_CASE_UPPER, "UTF-8");
            },
            'lowercase' => function($str) {
                return mb_convert_case($str, MB_CASE_LOWER, "UTF-8");
            },
            'ucfirst' => function($str) {
                return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
            },
            'ltrim' => function ($str, $charlist = " \t\n\r\0\x0B") {
                return ltrim($str, $charlist);
            },
            'rtrim' => function ($str, $charlist = " \t\n\r\0\x0B") {
                return rtrim($str, $charlist);
            },
            'itrim' => function ($str) {
                return mb_regex_encoding("\s{2,}", "", $str);
            },
        ];
    }
}
