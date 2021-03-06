<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

use Lang;
use October\Rain\Database\Collection;
use Cms\Classes\ComponentBase;
use Redhouse\Shelter\Models\Animal as AnimalModel;
use Redhouse\Shelter\Classes\TwigExtensions;

class Animals extends ComponentBase
{
    /**
     * @var Redhouse\Shelter\Model\Animal[] List of animals
     */
    public $animals;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.component.animals.name',
            'description' => 'redhouse.shelter::lang.component.animals.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'animalLimit' => [
                'title' => 'redhouse.shelter::lang.component.animals.animallimit',
                'type' => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'redhouse.shelter::lang.component.animals.animallimit_error',
                'default' => '0',
            ],
        ];
    }

    public function onRun()
    {
        $this->animals = $this->page['animals'] = $this->loadAnimals();
    }

    public function onRender()
    {
        if (empty($this->animals)) {
            $this->animals = $this->page['animals'] = $this->loadAnimals();
        }
    }

    /**
     * Returns instance of animal.
     */
    public function loadAnimals(): Collection
    {
        $limit = $this->getLimit();
        $animals = AnimalModel::notAdopted()->orderBy('id', 'desc');
        if ($limit) {
            $animals->take($limit);
        }

        $animals = $animals->get();
        foreach ($animals as $animal) {
            $this->setupAnimal($animal);
        }

        return $animals;
    }

    /**
     * Returns limit for selecting animals.
     */
    public function getLimit(): int
    {
        $limit = (int) $this->property('animalLimit');

        return $limit > 0 ? $limit : 0;
    }

    /**
     * Returns Animal instance complited with component attributes.
     */
    protected function setupAnimal(AnimalModel $animal): AnimalModel
    {
        // Set attributes for filter
        $months = $animal->getAge();
        $animal->ageInMonths = $months > 0 ? $months : 0;
        $animal->ageInDays = $months < 0 ? abs($months) : 0;
        // Set attributes for humans
        $animal->ageForHumans = $animal->ageInDays
            ? TwigExtensions::datepartLocal($animal->ageInDays, 'day')
            : TwigExtensions::age($animal->ageInMonths);
        $animal->sexForHumans = Lang::get('redhouse.shelter::lang.general.sex.'.$animal->sex);
        // Set profile picture
        $profilePic = $animal->featured_images->first();
        $animal->profilePic = $profilePic ? $profilePic->path : null;
        // Set url to animal page
        $animal->url = $this->controller->pageUrl('animal', ['slug' => $animal->slug]);

        return $animal;
    }
}
