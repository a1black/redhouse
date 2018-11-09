<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Classes;

use Lang;

class TwigExtensions
{
    /**
     * Returns pretty phone number.
     */
    public static function phoneNumber(string $number): string
    {
        if (strpos($number, '+7') === 0 || (strlen($number) == 11 && $number[0] === '8')) {
            $phone = substr($number, -10);
        } else {
            $phone = $number;
        }

        $phone = preg_replace_callback(
            '/^(\d{3})(\d{3})(\d{2})(\d{2})$/',
            function ($match) {
                if (count($match) == 5) {
                    return sprintf('+7(%d) %d-%d-%d', ...array_slice($match, 1));
                }
            },
            $phone
        );

        return $phone ?: $number;
    }

    /**
     * Returns string with correct verb ending.
     */
    public static function verbGender(string $str, string $gender): string
    {
        return sprintf(
            '%s%s',
            $str,
            Lang::choice('redhouse.shelter::lang.general.verbend', $gender == 'female')
        );
    }

    /**
     * Returns localazed date part.
     *
     * Supported values: day, month, year
     */
    public static function datepartLocal(int $count, string $unit): string
    {
        return sprintf(
            '%d %s',
            $count,
            Lang::choice("redhouse.shelter::lang.general.datepart.{$unit}", $count)
        );
    }

    /**
     * Returns age in format 'Y m'.
     *
     * @param int $months age in months
     */
    public static function age($months): string
    {
        $years = (int) floor($months / 12);
        $months -= $years * 12;

        $age = [];
        if ($years) {
            $age[] = self::datepartLocal($years, 'year');
        }

        if ($months) {
            $age[] = self::datepartLocal($months, 'month');
        }

        return $age
            ? implode(' ', $age)
            : Lang::get('redhouse.shelter::lang.general.not_born');
    }
}
