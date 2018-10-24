<?php
return [
    'plugin' => [
        'name' => 'Twig Extensions',
        'description' => 'Расширения для Twig, реобходимые для проекта RedHouse.',
    ],
    'errors' => [
        'invalid_birthday' => 'Некорректная дата рождения \':msg\'.',
    ],
    'age' => [
        'years' => '{1}:number год|[2,4]:number года|[5,Inf]:number лет',
        'months' => '{1}:number месяц|[2,4]:number месяца|[5,Inf]:number месяцев',
    ],
    'since' => [
        'minutes' => '{1}минуту назад|[2,4]:number минуты назад|[5,Inf]:number минут назад',
        'hours' => '{1}час назад|[2,4]:number часа назад|[5,Inf]:number часов назад',
        'days' => '{1}вчера|[2,4]:number дня назад|[5,Inf]:number дней назад',
        'now' => 'сейчас',
        'yesterday' => 'вчера',
    ],
];
