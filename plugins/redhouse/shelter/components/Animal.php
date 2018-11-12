<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

use Lang;
use October\Rain\Database\Collection;
use Cms\Classes\ComponentBase;
use Redhouse\Shelter\Models\Animal as AnimalModel;
use Redhouse\Shelter\Classes\TwigExtensions;

class Animal extends ComponentBase
{
    /**
     * @var Redhouse\Shelter\Model\Animal Animal information
     */
    public $animal;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.component.animal.name',
            'description' => 'redhouse.shelter::lang.component.animal.description',
        ];
    }

    /**
     * Returns description for component properties.
     */
    public function defineProperties(): array
    {
        return [
            'slug' => [
                'title' => 'rainlab.blog::lang.component.animal.slug_label',
                'description' => 'rainlab.blog::lang.component.animal.slug_desc',
                'default' => '{{ :slug }}',
                'type' => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $this->animal = $this->page['animal'] = $this->loadAnimal();
    }

    public function onRender()
    {
        if (empty($this->animal)) {
            $this->animal = $this->page['animal'] = $this->loadAnimal();
        }
    }

    /**
     * Returns instance of animal.
     */
    public function loadAnimal(): ?AnimalModel
    {
        $animal = AnimalModel::where('slug', $this->property('slug'))->first();

        return $animal ? $this->setupAnimal($animal) : null;
    }

    /**
     * Returns Animal complited with component attributes.
     */
    protected function setupAnimal(AnimalModel $animal): AnimalModel
    {
        // Set animal age for filter and display
        $months = $animal->getAge();
        $animal->ageForHumans = $months < 0
            ? TwigExtensions::datepartLocal(abs($months), 'day')
            : TwigExtensions::age($months);
        // Set attributes displayed to humans
        $animal->sexForHumans = Lang::get('redhouse.shelter::lang.general.sex.'.$animal->sex);
        $animal->healthForHumans = Lang::choice(
            'redhouse.shelter::lang.general.health.'.$animal->health,
            $animal->sex === AnimalModel::SEX_F
        );
        // Set profile picture
        $profilePic = $animal->featured_images->first();
        if ($profilePic) {
            $animal->profilePic = $profilePic->path;
            $animal->photos = $animal->featured_images->except($profilePic->id);
        } else {
            $animal->profilePic = null;
            $animal->photos = $animal->featured_images;
        }

        return $animal;
    }
}
