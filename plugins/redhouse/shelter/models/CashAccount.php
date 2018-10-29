<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use Illuminate\Support\Fluent;
use Illuminate\Validation\Validator;
use October\Rain\Database\Model;
use October\Rain\Database\Builder;
use October\Rain\Database\Traits\Validation;

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
     * @inheritdoc
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
        'transfer_url' => 'sometimes|url',
    ];

    /**
     * Custom attribute names used by validator.
     *
     * @var array
     */
    public $attributeNames = [
    ];

    /**
     * Custom validation error messages.
     *
     * @var array
     */
    public $customMessages = [
    ];

    /**
     * Returns list of money account types.
     */
    public function listTypes(): array
    {
        $list = [];
        foreach (self::$cashAccountTypes as $type) {
            $list[$type] = 'redhouse.shelter::lang.cashaccount.type.'.$type;
        }

        return $list;
    }

    /**
     * Returns midel data validator.
     */
    public function makeValidator(
        array $data,
        array $rules,
        array $customMessages = [],
        array $attributeNames = []
    ): Validator {
        $validator = self::traitMakeValidator($data, $rules, $customMessages, $attributeNames);

        // Extend validator
        Validator::extend('bank', function ($attribute, $value, $parameters) {
            return preg_match('/^[\pL\pM\pN\s\x22\x27\x2C\x2D\x2E\x5F\xAB\xBB]+$/u', $value) > 0;
        });

        // Runtime dependent rules
        $extraRules = [
            'type' => sprintf('in:%s', implode(',', self::$cashAccountTypes)),
            'account' => sprintf('unique:%s,account,NULL,id,type,%s', $this->table, $this->type),
        ];
        $validator->addRules($extraRules);

        // Make simple Closer factroy
        $typeCheck = function ($type) {
            return function (Fluent $request) use ($type) {
                return $request->get('type') === $type;
            };
        };

        // Bank type dependant rules
        $validator->sometimes('bank_name', 'required|bank', $typeCheck(self::CA_TYPE_BANK));
        $validator->sometimes('account', 'digits:20', $typeCheck(self::CA_TYPE_BANK));
        $validator->sometimes('account', 'email', $typeCheck(self::CA_TYPE_PAYPAL));
        $validator->sometimes('account', 'regex:/^\d{12,}$/', $typeCheck(self::CA_TYPE_YANDEX));
        $validator->sometimes('bank_id_code', 'required|digits:9', $typeCheck(self::CA_TYPE_BANK));
        $validator->sometimes('correspondent', 'required|digits:20', $typeCheck(self::CA_TYPE_BANK));
    }

    public function beforeSave()
    {
        if (in_array($this->type, [self::CA_TYPE_YANDEX, self::CA_TYPE_PAYPAL])) {
            $this->bank_name = '';
        }
    }

    /**
     * Returns complited with account number matching condition.
     */
    public function scopeAccountLike(Builder $query, string $value): Builder
    {
        if (strlen($vlaue) > 4) {
            $query->where('account', 'like', "%$value%");
        }

        return $query;
    }
}
