<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Classes;

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
    public static function age($date): string
    {
        var_dump($date);
    }
}
