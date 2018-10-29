<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation;
use System\Behaviors\SettingsModel;

/**
 * Organization tax information.
 */
class TaxPayerInfo extends Model
{
    use Validation;

    /**
     * Unique identifier under which settings are stored.
     *
     * @var string
     */
    public $settingsCode = 'redhouse_shelter_taxinfo';

    /** @var array */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * Form fields definitions.
     *
     * @var string
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Validation rules.
     *
     * @var array
     */
    public $rules = [
        'fullname' => 'required|alpha|min:3|min:255',
        'tax_id' => 'required|digits:10',
        'tax_number' => 'sometimes|required|digits:9',
        'purpose' => 'required|alpha|min:10|max:255',
    ];

    /**
     * Custom attribute names used by validator.
     *
     * @var array
     */
    public $customAttributes = [
        'fullname' => 'redhouse.shelter::lang.taxinfo.fullname_label',
        'tax_id' => 'redhouse.shelter::lang.taxinfo.tax_id_label',
        'tax_number' => 'redhouse.shelter::lang.taxinfo.tax_number_label',
        'purpose' => 'redhouse.shelter::lang.taxinfo.purpose_label',
    ];

    /**
     * Custom validation messages
     *
     * @var array
     */
    public $customMessages = [
        'digits' => 'redhouse.shelter::lang.taxinfo.error.digits',
    ];

    /**
     * Modifies data before saving it.
     */
    public function beforeSave()
    {
        $this->fullname = mb_convert_case($this->fullname, MB_CASE_TITLE);
        $this->purpose = ucfirst(mb_convert_case($this->purpose, MB_CASE_LOWER));
    }
}
