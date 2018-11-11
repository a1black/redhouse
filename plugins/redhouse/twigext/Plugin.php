<?php

namespace Redhouse\TwigExt;

use \Lang;
use \Carbon\Carbon;
use \System\Classes\PluginBase;

class Plugin extends PluginBase {
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
        $filters['period'] = [$this, 'makeTimePeriod'];

        return [
            'filters' => $filters,
        ];
    }

    /**
     * Returns date or time difference between provided date and current time or
     * date in specified format.
     * Recognazed time periods: 'X (minutes|hours|days) ago', 'now', 'yesterday'.
     *
     * @return string
     */
    public function makeTimePeriod($datetime, $format = 'd M Y')
    {
        $datetime = new Carbon($datetime);

        $diff = $datetime->diffInSeconds(Carbon::now(), false);
        if ($diff < 60) {
            $str = Lang::get('redhouse.twigext::lang.since.now');
        } else if ($diff < 60 * 60) {
            $str = Lang::choice('redhouse.twigext::lang.since.minutes', floor($diff / (60 * 60)));
        } elseif ($diff < 60 * 60 * 24) {
            $str = Lang::choice('redhouse.twigext::lang.since.hours', floor($diff / (60 * 60)));
        } elseif ($diff < 60 * 60 * 24 * 2) {
            $str = Lang::get('redhouse.twigext::lang.since.yesterday');
        } else if ($diff < 60 * 60 * 24 * 7) {
            $str = Lang::choice('redhouse.twigext::lang.since.days', floor($diff / (60 * 60 * 24)));
        } else if ($datetime->diffInYears(Carbon::now(), false) < 1) {
            // I know it breaks US format dates, but project not intended to be used outside RU locale.
            $str = $datetime->format(mb_ereg_replace('(y|Y)\W?', '', $format));
        } else {
            $str = $datetime->format($format);
        }

        return $str;
    }

    /**
     * Converts birthday date into 'X years Y months' string.
     *
     * @return string
     */
    public function makeAge($birthday)
    {
        $birthday = new Carbon($birthday);
        $now = Carbon::now();
        $years = $birthday->diffInYears($now, false);
        $months = $birthday->diffInMonths($now, false) - $years * 12;
        if ($months < 1 && $years < 1) {
            throw new InvalidArgumentException(Lang::get(
                'redhouse.twigext::lang.errors.invalid_birthday',
                ['msg' => $birthday]
            ));
        }
        $str = [
            $years
                ? Lang::choice(
                    'redhouse.twigext::lang.age.years',
                    $years,
                    ['number' => $years])
                : '',
            $months
                ? Lang::choice(
                    'redhouse.twigext::lang.age.months',
                    $months,
                    ['number' => $months])
                : '',
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
                return mb_ereg_replace("\s{2,}", "", $str);
            },
        ];
    }
}
