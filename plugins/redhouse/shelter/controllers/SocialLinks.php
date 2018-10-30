<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Controllers;

use Lang;
use Flash;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use Backend\Behaviors\FormController;
use Redhouse\Shelter\Models\SocialLink;

/**
 * Management of social links.
 */
class SocialLinks extends Controller
{
    /** @var array */
    public $implement = [
        'Backend.Behaviors.FormController',
    ];

    /** @var string */
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Redhouse.Shelter', 'shelter', 'social');
    }

    public function index()
    {
        $this->pageTitle = 'redhouse.shelter::lang.view.social.form';
        $model = SocialLink::instance();
        if ($model->id) {
            $this->asExtension('FormController')->update($model->id);
        } else {
            $this->asExtension('FormController')->create();
        }
    }

    /**
     * AJAX handler for form save action.
     */
    public function index_onSave($recordId = null, $context = null)
    {
        $model = SocialLink::instance();
        if ($model->id) {
            $context = 'update';
            $this->asExtension('FormController')->update_onSave($model->id, $context);
        } else {
            $context = 'create';
            $this->asExtension('FormController')->create_onSave($context);
        }

        return $this->makeRedirect($context) ?? Redirect::refresh();
    }
}
