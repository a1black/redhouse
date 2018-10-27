<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use October\Rain\Database\Model;

/**
 * Organization contact information.
 */
class Contact extends Model
{
    /**
     * @inheritdoc
     */
    public $table = 'redhouse_shelter_contacts';

    /** @var array */
    public $hasMany = [
        'numbers' => ['Redhouse\Shelter\Models\ContactNumber'],
    ];

    public function getNumberCountAttribute()
    {
        return $this->numbers->count();
    }

    /**
     * Allows filtering for specific contact number.
     *
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  string                    $categories Contuct number
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeFilterContactNumber($query, $number)
    {
        return $query;
    }
}
