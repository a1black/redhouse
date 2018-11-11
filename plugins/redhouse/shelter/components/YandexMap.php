<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

use Cms\Classes\Controller;
use Cms\Classes\ComponentBase;
use Redhouse\Shelter\Models\YandexMap as YandexMapSettings;

class YandexMap extends ComponentBase
{
    /**
     * @var Redhouse\Shelter\Models\YandexMap map API settings
     */
    public $settings;

    /**
     * @var string HTML object identifier
     */
    public $id;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.component.yandexmap.name',
            'description' => 'redhouse.shelter::lang.component.yandexmap.description',
        ];
    }

    /**
     * Returns list of component options.
     */
    public function defineProperties(): array
    {
        return [
            'zoom' => [
                'title' => 'Zoom',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
            ],
            'class' => [
                'title' => 'CSS',
                'type' => 'string',
            ],
            'width' => [
                'title' => 'redhouse.shelter::lang.component.yandexmap.width',
                'type' => 'string',
                'validationPattern' => '^[\w\s_%-]+$',
                'validationMessage' => 'redhouse.shelter::lang.component.yandexmap.size_error',
            ],
            'height' => [
                'title' => 'redhouse.shelter::lang.component.yandexmap.height',
                'type' => 'string',
                'validationPattern' => '^[\w\s_%-]+$',
                'validationMessage' => 'redhouse.shelter::lang.component.yandexmap.size_error',
            ],
        ];
    }

    public function onRun()
    {
        $this->settings = $this->page['settings'] = YandexMapSettings::instance();
        $this->id = uniqid("ymap");

        $this->addJs($this->apiJs());
    }

    public function onRender()
    {
        if (empty($this->settings)) {
            $this->settings = $this->page['settings'] = YandexMapSettings::instance();
        }
    }

    /**
     * Returns URL of Yandex.Map JS API.
     */
    public function apiJs(): string
    {
        return sprintf(
            'https://api-maps.yandex.ru/2.1/?apikey=%s&lang=%s',
            $this->settings->apikey,
            'ru_RU'
        );
    }

    /**
     * Returns true if map enabled and propely configured.
     */
    public function enabled(): bool
    {
        return $this->settings->enabled
            && $this->settings->apikey
            && $this->settings->latitude
            && $this->settings->longitude;
    }

    /**
     * Returns organization geo coordinates.
     */
    public function coordinates(): array
    {
        return [$this->settings->latitude, $this->settings->longitude];
    }

    /**
     * Returns description of map marker.
     */
    public function location(): array
    {
        return [
            'title' => $this->settings->title,
            'description' => $this->settings->description,
        ];
    }
}
