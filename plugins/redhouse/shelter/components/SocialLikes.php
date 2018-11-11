<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

use Cms\Classes\Controller;
use Cms\Classes\ComponentBase;
use October\Rain\Support\Collection;
use Redhouse\Shelter\Models\SocialLink as SLModel;

class SocialLikes extends ComponentBase
{
    /**
     * @var October\Rain\Support\Collection list of links
     */
    public $links;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.component.sociallikes.name',
            'description' => 'redhouse.shelter::lang.component.sociallikes.description',
        ];
    }

    /**
     * Returns list of component options.
     */
    public function defineProperties(): array
    {
        return [
            'facebook' => [
                'title' => 'redhouse.shelter::lang.social.fb_label',
                'description' => 'redhouse.shelter::lang.social.fb_toggle_label',
                'group' => 'redhouse.shelter::lang.component.sociallikes.toggle',
                'default' => 1,
                'type' => 'checkbox',
            ],
            'vk' => [
                'title' => 'redhouse.shelter::lang.social.vk_label',
                'description' => 'redhouse.shelter::lang.social.vk_toggle_label',
                'group' => 'redhouse.shelter::lang.component.sociallikes.toggle',
                'default' => 1,
                'type' => 'checkbox',
            ],
            'odnoklassniki' => [
                'title' => 'redhouse.shelter::lang.social.odnoklassniki_label',
                'description' => 'redhouse.shelter::lang.social.odnoklassniki_toggle_label',
                'group' => 'redhouse.shelter::lang.component.sociallikes.toggle',
                'default' => 1,
                'type' => 'checkbox',
            ],
        ];
    }

    public function onRun()
    {
        $this->links = $this->page['links'] = $this->loadLinks();
    }

    public function onRender()
    {
        if (empty($this->links)) {
            $this->links = $this->page['links'] = $this->loadLinks();
        }
    }

    /**
     * Returns URLs to social networks.
     */
    public function loadLinks(): Collection
    {
        $controller = Controller::getController() ?: new Controller();
        $pageUrl = $controller->currentPageUrl();
        $links = new Collection([]);

        if ($pageUrl) {
            if ($this->property('facebook')) {
                $links->put('facebook', [
                    'url' => 'https://www.facebook.com/sharer.php?u='.urlencode($pageUrl),
                    'icon' => 'fab fa-facebook-f',
                ]);
            }
            if ($this->property('vk')) {
                $links->put('vk', [
                    'url' => 'https://vk.com/share.php?url='.urlencode($pageUrl),
                    'icon' => 'fab fa-vk',
                ]);
            }
            if ($this->property('odnoklassniki')) {
                $links->put('odnoklassniki', [
                    'url' => 'https://connect.ok.ru/offer?url='.urlencode($pageUrl),
                    'icon' => 'fab fa-odnoklassniki',
                ]);
            }
        }

        return $links;
    }
}
