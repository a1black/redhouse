<?php
return [
    'plugin' => [
        'name' => 'Twig Extensions',
        'description' => 'Twig extensions required by RedHouse project.',
    ],
    'errors' => [
        'invalid_birthday' => 'Invalid birthday date \':msg\'.',
    ],
    'age' => [
        'years' => 'an year|[2,Inf]:number years',
        'months' => 'a month|[2,Inf]:number months',
    ],
    'since' => [
        'minutes' => 'a minute ago|[2,Inf]:number minutes ago',
        'hours' => 'an hour ago|[2,Inf]:number hours ago',
        'days' => 'yesterday|[2,Inf]:number days ago',
        'now' => 'now',
        'yesterday' => 'yesterday',
    ],
];
