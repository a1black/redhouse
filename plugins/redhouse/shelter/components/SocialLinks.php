<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Support\Collection;
use Redhouse\Shelter\Models\SocialLink as SLModel;

class SocialLinks extends ComponentBase
{
    /**
     * @var October\Rain\Support\Collection List of links.
     */
    public $links;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.component.sociallinks.name',
            'description' => 'redhouse.shelter::lang.component.sociallinks.description',
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
        $links = new Collection([]);
        foreach (array_filter(SLModel::instance()->attributes) as $linkName => $url) {
            switch ($linkName) {
                case 'fb_link':
                    $links->put('facebook', ['url' => $url, 'icon' => 'fab fa-facebook-f']);
                    break;
                case 'vk_link':
                    $links->put('vk', ['url' => $url, 'icon' => 'fab fa-vk']);
                    break;
                case 'odnoklassniki_link':
                    $links->put('odnoklassniki', ['url' => $url, 'icon' => 'fab fa-odnoklassniki']);
                    break;
                case 'google_link':
                    $links->put('google+', ['url' => $url, 'icon' => 'fab fa-google_plus']);
                    break;
                case 'twitter_link':
                    $links->put('twitter', ['url' => $url, 'icon' => 'fab fa-twitter']);
                    break;
                case 'instagram_link':
                    $links->put('instagram', ['url' => $url, 'icon' => 'fab fa-instagram']);
                    break;
                case 'livejournal_link':
                    $links->put('livejournal', ['url' => $url, 'icon' => 'fas fa-pencil-alt']);
                    break;
            }
        }

        return $links;
    }
}
