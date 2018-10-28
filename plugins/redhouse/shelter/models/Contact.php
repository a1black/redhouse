<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use October\Rain\Database\Model;
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

    public function getNumberCountAttribute()
    {
        return $this->numbers->count();
    }

    /**
     * Apply where condition to list query for filtering by contuct number.
     *
     * @see Redhouse\Shelter\Models\ContuctNumber::scopeNumberLike
     *
     * @param  Illuminate\Query\Builder  $query      QueryBuilder
     * @param  string                    $categories Contuct number
     * @return Illuminate\Query\Builder              QueryBuilder
     */
    public function scopeFilterContactNumber($query, $number = null)
    {
        if (strlen($number) >= 4) {
            $query->whereHas('numbers', function ($query) use ($number) {
                $query->numberLike($number);
            });
        }
        //var_dump($query->toSql());
        return $query;
        //$numlen = strlen($number);
        //if (!is_numeric($number) && $numlen > 4) {
        //    $query->numbers()->where('type', ContactNumber::CN_TYPE_MOBILE);
        //    $query->numbers()->where('number', 'like', $number.'%');
        //} elseif (is_numeric($number) && $numlen >= 4) {
        //    if ($numlen > ContactNumber::MOBILE_LEN) {
        //        $query->where('id', 0);
        //    } elseif ($numlen == ContactNumber::MOBILE_LEN) {
        //        $query->numbers->where('number', $number);
        //    } else {
        //        $ptn = str_pad('', ContactNumber::MOBILE_LEN - strlen($number), '_');
        //        $query->numbers()->where('number', 'like', $ptn.$number);
        //    }
        //}

        //return $query;
    }
}
