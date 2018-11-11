<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Controllers;

use Lang;
use Flash;
use Redirect;
use BackendMenu;
use Backend\Classes\Controller;
use Backend\Behaviors\FormController;
use Redhouse\Shelter\Models\YandexMap as YandexMapSettings;

/**
 * Management of tax details.
 */
class YandexMap extends Controller
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

        BackendMenu::setContext('Redhouse.Shelter', 'shelter', 'yandexmap');
    }

    public function index()
    {
        $this->pageTitle = 'redhouse.shelter::lang.view.yandexmap.form';
        $model = YandexMapSettings::instance();
        if ($model->id) {
            $this->asExtension('FormController')->update($model->id);
        } else {
            $this->asExtension('FormController')->create();
        }
    }

    /**
     * AJAX handler for form save action.
     *
     * @param null|int    $recordId record identifier
     * @param null|string $context  form context
     */
    public function index_onSave($recordId = null, $context = null)
    {
        $model = YandexMapSettings::instance();
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
