<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Classes;

use Lang;
use Carbon\Carbon;

class ListTypeHelpers
{
    /**
     * Returns pretty phone number.
     */
    public static function prettyPhoneNumber(string $number): string
    {
        $phone = preg_replace_callback(
            '/^(\d{3})(\d{3})(\d{2})(\d{2})$/',
            function ($match) {
                if (count($match) == 5) {
                    return sprintf('+7(%d) %d-%d-%d', ...array_slice($match, 1));
                }
            },
            $number
        );

        return $phone ?: $number;
    }

    /**
     * Returns age using birthday.
     */
    public static function age(Carbon $date): string
    {
        $parts = [];
        $diff = $date->diff(Carbon::now(), false);
        $diffParts = [
            ['value' => $diff->y, 'unit' => 'y'],
            ['value' => $diff->m, 'unit' => 'm'],
            ['value' => $diff->d, 'unit' => 'd'],
        ];

        foreach ($diffParts as $part) {
            if ($part['value'] > 0) {
                $parts[] = sprintf(
                    '%d %s',
                    $part['value'],
                    Lang::get('redhouse.shelter::lang.general.datepart.'.$part['unit'])
                );
            }
        }

        return count($parts) && !$diff->invert
            ? implode(' ', $parts)
            : Lang::get('redhouse.shelter::lang.general.not_born');
    }
}
