<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Controllers;

use Lang;
use Flash;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
//use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Redhouse\Shelter\Models\CashAccount;
use Redhouse\Shelter\Models\TaxPayerInfo;

/**
 * Money accounts management.
 */
class CashAccounts extends Controller
{
    public $implement = [
        //'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    /** @var string */
    //public $formConfig = 'config_form.yaml';

    /** @var string */
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Redhouse.Shelter', 'shelter', 'cashaccounts');
    }

    public function index()
    {
        parent::index();

        $taxinfo = TaxPayerInfo::instance();
        if (!$taxinfo->id) {
            $this->vars['errorNoTax'] = 'redhouse.shelter::lang.view.taxinfo.empty_msg';
        } else {
            $this->vars['taxrecord'] = $taxinfo;
        }
    }
}
