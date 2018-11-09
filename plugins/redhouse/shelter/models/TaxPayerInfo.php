<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Html;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
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
        'fullname' => 'required|min:3|max:255',
        'tax_id' => ['required', 'regex:/^(\d{10}|\d{12})$/'],
        'tax_number' => 'sometimes|required|digits:9',
        'purpose' => 'required|min:10|max:255',
    ];

    /**
     * Custom validation messages
     *
     * @var array
     */
    public $customMessages = [
        'tax_id.regex' => 'redhouse.shelter::lang.taxinfo.error.tax_id',
        'tax_number.digits' => 'redhouse.shelter::lang.taxinfo.error.tax_number',
    ];

    /**
     * Modifies data before processing it.
     */
    public function beforeValidate()
    {
        $this->fullname = Html::entities(Html::strip($this->fullname));
        $this->purpose = Html::entities(Html::strip($this->purpose));
    }
}
