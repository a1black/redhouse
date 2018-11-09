<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

//use October\Rain\Database\Collection;
use October\Rain\Support\Collection;
use Cms\Classes\ComponentBase;
use Redhouse\Shelter\Models\CashAccount;
use Redhouse\Shelter\Models\TaxPayerInfo;

class MoneyAccounts extends ComponentBase
{
    /**
     * @var Redhouse\Shelter\Model\CashAccount[] List of contacts
     */
    public $accounts;

    /**
     * @var Redhouse\Shelter\Model\TaxPayerInfo Company tax information
     */
    public $taxpayer;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.component.moneyaccounts.name',
            'description' => 'redhouse.shelter::lang.component.moneyaccounts.description',
        ];
    }

    public function onRun()
    {
        $this->accounts = $this->page['accounts'] = $this->loadAccounts();
        $this->taxpayer = $this->page['taxpayer'] = $this->loadTaxPayerInfo();
    }

    public function onRender()
    {
        if (empty($this->accounts)) {
            $this->accounts = $this->page['accounts'] = $this->loadAccounts();
        }

        if (empty($this->taxpayer)) {
            $this->taxpayer = $this->page['taxpayer'] = $this->loadTaxPayerInfo();
        }
    }

    /**
     * Returns list of money accounts information.
     */
    public function loadAccounts(): Collection
    {
        $accounts = new Collection([]);
        foreach (CashAccount::$cashAccountTypes as $type) {
            $accounts->put($type, CashAccount::where('type', $type)->get());
        }

        return $accounts;
    }

    /**
     * Returns company tax details.
     */
    public function loadTaxPayerInfo(): TaxPayerInfo
    {
        return TaxPayerInfo::instance();
    }
}
