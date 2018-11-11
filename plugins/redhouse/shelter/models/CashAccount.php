<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;
use Redhouse\Shelter\Classes\ValidatorExtensions;

/**
 * Phone number for specific contact.
 */
class CashAccount extends Model
{
    use Validation {
        makeValidator as traitMakeValidator;
    }

    const CA_TYPE_BANK = 'bank';
    const CA_TYPE_YANDEX = 'yandex';
    const CA_TYPE_PAYPAL = 'paypal';

    /** @var array */
    public static $cashAccountTypes = [
        self::CA_TYPE_BANK,
        self::CA_TYPE_YANDEX,
        self::CA_TYPE_PAYPAL,
    ];

    /**
     * {@inheritdoc}
     */
    public $table = 'redhouse_shelter_cash_accounts';

    /**
     * Validation rules.
     *
     * @return array
     */
    public $rules = [
        'type' => 'required',
        'account' => 'required',
    ];

    /**
     * Custom validation errors.
     *
     * @var array
     */
    public $customMessages = [
        'bank_name.alpha_text' => 'redhouse.shelter::lang.cashaccount.error.bank',
    ];

    /**
     * Returns data validator.
     */
    public function makeValidator(
        array $data,
        array $rules,
        array $customMessages = [],
        array $attributeNames = []
    ): Validator {
        $validator = self::traitMakeValidator($data, $rules, $customMessages, $attributeNames);

        // Extend validator
        ValidatorExtensions::apply($validator);

        // Runtime dependent rules
        $extraRules = [
            'type' => sprintf('in:%s', implode(',', self::$cashAccountTypes)),
            'account' => sprintf('unique:%s,account,%s,id,type,%s', $this->table, $this->id ?: 'NULL', $this->type),
        ];
        $validator->addRules($extraRules);

        // Make simple Closer factroy
        $typeCheck = function ($type) {
            return function (Fluent $request) use ($type) {
                return $request->get('type') === $type;
            };
        };

        // Bank type dependant rules
        $validator->sometimes('bank_name', 'required|alpha_text', $typeCheck(self::CA_TYPE_BANK));
        $validator->sometimes('account', 'digits:20', $typeCheck(self::CA_TYPE_BANK));
        $validator->sometimes('account', 'email', $typeCheck(self::CA_TYPE_PAYPAL));
        $validator->sometimes('account', 'regex:/^\d{12,}$/', $typeCheck(self::CA_TYPE_YANDEX));
        $validator->sometimes('bank_id_code', 'required|digits:9', $typeCheck(self::CA_TYPE_BANK));
        $validator->sometimes('correspondent', 'required|digits:20', $typeCheck(self::CA_TYPE_BANK));
        // Optional rules
        $validator->sometimes(
            'transfer_url',
            'url',
            function (Fluent $request) {
                return isset($this->transfer_url);
            }
        );

        return $validator;
    }

    public function beforeSave()
    {
        if (\in_array($this->type, [self::CA_TYPE_YANDEX, self::CA_TYPE_PAYPAL])) {
            $this->bank_name = null;
            $this->bank_id_code = null;
            $this->correspondent = null;
        }
    }

    /**
     * Returns complited with account number matching condition.
     */
    public function scopeAccountLike(Builder $query, string $value): Builder
    {
        if (\strlen($value) > 4) {
            $query->where('account', 'like', "%$value%");
        }

        return $query;
    }

    public function getTypeOptions(): array
    {
        $options = [];
        foreach (self::$cashAccountTypes as $type) {
            $options[$type] = 'redhouse.shelter::lang.cashaccount.type.'.$type;
        }

        return $options;
    }
}
