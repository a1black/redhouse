<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Management of tax details.
 */
class Settings extends Controller
{
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Redhouse.Shelter', 'shelter', 'settings');
    }

    public function index()
    {
        $this->pageTitle = 'redhouse.shelter::lang.nav.settings.label';
    }
}
