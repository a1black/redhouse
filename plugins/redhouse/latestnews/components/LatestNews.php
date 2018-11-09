<?php

declare(strick_types=1);

namespace Redhouse\LatestNews\Components;

use Carbon\Carbon;
use October\Rain\Database\Collection;
use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post;

class LatestNews extends ComponentBase
{
    /**
     * @var Redhouse\Shelter\Model\Post[] List of animals
     */
    public $posts;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.latestnews::lang.latestnews.name',
            'description' => 'redhouse.latestnews::lang.latestnews.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'postLimit' => [
                'title' => 'redhouse.latestnews::lang.settings.postlimit',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'redhouse.latestnews::lang.settings.postlimit_error',
                'default' => '3',
            ],
            'daysAgo' => [
                'title' => 'redhouse.latestnews::lang.settings.daysago',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'redhouse.latestnews::lang.settings.daysago_error',
                'default' => '7',
            ],
        ];
    }

    public function onRun()
    {
        $this->posts = $this->page['posts'] = $this->loadPosts();
    }

    public function onRender()
    {
        if (empty($this->posts)) {
            $this->posts = $this->page['posts'] = $this->loadPosts();
        }
    }

    /**
     * Returns list of latest posts.
     */
    public function loadPosts(): Collection
    {
        $days = $this->getDays();
        $posts = Post::isPublished()->orderBy('published_at', 'desc')->take($this->getLimit())->get();

        $hasNew = array_reduce($posts->getDictionary(), function ($carry, $item) use ($days) {
            return $carry || $item->published_at->diffInDays(Carbon::now()) <= $days;
        });

        return $hasNew ? $posts : new Collection([]);
    }

    /**
     * Returns number of posts in the feed.
     */
    public function getLimit(): int
    {
        $limit = (int) $this->property('postLimit');
        return $limit > 0 ? $limit : 3;
    }

    /**
     * Returns number of days after which post wan't show-up in the feed.
     */
    public function getDays(): int
    {
        $days = (int) $this->property('daysAgo');
        return $days > 0 ? $days : 7;
    }
}
