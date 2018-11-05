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
use Cms\Classes\Controller as CmsController;
use Redhouse\Shelter\Models\Animal;

/**
 * Shelter animal catalog.
 */
class Animals extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    /** @var string */
    public $formConfig = 'config_form.yaml';

    /** @var string */
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Redhouse.Shelter', 'shelter', 'animals');
    }

    public function update($recordId, $context = null)
    {
        parent::update($recordId, $context);

        $this->vars['viewOnSite'] = (new CmsController)->pageUrl(
            'animal',
            ['slug' => $this->vars['formModel']->slug]
        );
    }
}
