<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Controllers;

use Lang;
use Flash;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;
use Backend\Behaviors\RelationController;
use Redhouse\Shelter\Models\Contact;
use Redhouse\Shelter\Models\ContactNumber;

/**
 * Contact details management.
 */
class Contacts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    /** @var string */
    public $formConfig = 'config_form.yaml';

    /** @var string */
    public $listConfig = 'config_list.yaml';

    /** @var string */
    public $relationConfig = 'config_relation.yaml';

    public $hasMany = [
        'numbers' => ['Redhouse\Shelter\Models\ContactNumber'],
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Redhouse.Shelter', 'shelter', 'contacts');
    }
}