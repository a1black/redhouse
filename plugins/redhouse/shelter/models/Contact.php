<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;
use Redhouse\Shelter\Models\ContactNumber;

/**
 * Organization contact information.
 */
class Contact extends Model
{
    use Validation;

    /**
     * @inheritdoc
     */
    public $table = 'redhouse_shelter_contacts';

    /** @var array */
    public $hasMany = [
        'numbers' => ['Redhouse\Shelter\Models\ContactNumber', 'delete' => true],
    ];

    /**
     * Validation rules.
     *
     * @return array
     */
    public $rules = [
        'name' => 'required|min:2|max:20',
        'note' => 'max:100',
        'description' => 'max:255',
    ];

    /**
     * Custom attribute names used by validator.
     *
     * @var array
     */
    public $attributeNames = [
        'name' => 'redhouse.shelter::lang.contact.name_label',
        'note' => 'redhouse.shelter::lang.contact.note_label',
        'description' => 'redhouse.shelter::lang.contact.description_label',
    ];

    /**
     * Returns count of contuct numbers.
     */
    public function getNumberCountAttribute(): int
    {
        return $this->numbers->count();
    }

    /**
     * Apply where condition to list query for filtering by contuct number.
     *
     * @see Redhouse\Shelter\Models\ContuctNumber::scopeNumberLike
     */
    public function scopeFilterContactNumber(Builder $query, string $number): Builder
    {
        if (strlen($number) >= 4) {
            $query->whereHas('numbers', function ($query) use ($number) {
                $query->numberLike($number);
            });
        }

        return $query;
    }
}
