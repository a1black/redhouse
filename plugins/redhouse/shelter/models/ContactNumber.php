<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use October\Rain\Database\Model;

/**
 * Phone number for specific contact.
 */
class ContactNumber extends Model
{
    const CN_TYPE_MOBILE = 'mobil';
    const CN_TYPE_SKYPE = 'skype';
    const CN_TYPE_VIBER = 'viber';

    public static $contactNumberTypes = [
        self::CN_TYPE_MOBILE,
        self::CN_TYPE_SKYPE,
        self::CN_TYPE_VIBER,
    ];

    /**
     * @inheritdoc
     */
    public $table = 'redhouse_shelter_contact_numbers';

    /** @var array */
    public $belongsTo = [
        'contact' => ['Redhouse\Shelter\Models\Contact'],
    ];

    /**
     * Returns list of contuct number types.
     *
     * @return array
     */
    public function listTypes()
    {
        $list = [];
        foreach (self::$contactNumberTypes as $type) {
            $list[$type] = 'redhouse.shelter::lang.contact_number.type.'.$type;
        }

        return $list;
    }
}
