<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

use October\Rain\Database\Collection;
use Cms\Classes\ComponentBase;
use Redhouse\Shelter\Models\Contact;

class Contacts extends ComponentBase
{
    /**
     * @var Redhouse\Shelter\Model\Contact[] List of contacts
     */
    public $contacts;

    /**
     * Returns component details.
     */
    public function componentDetails(): array
    {
        return [
            'name' => 'redhouse.shelter::lang.component.contacts.name',
            'description' => 'redhouse.shelter::lang.component.contacts.description',
        ];
    }

    public function onRun()
    {
        $this->contacts = $this->page['contacts'] = $this->loadContacts();
    }

    public function onRender()
    {
        if (empty($this->contacts)) {
            $this->contacts = $this->page['contacts'] = $this->loadContacts();
        }
    }

    /**
     * Returns list of published contacts.
     */
    public function loadContacts(): Collection
    {
        $contacts = Contact::isPublished()->orderBy('name')->get();
        foreach ($contacts as $contact) {
            $numbers = $contact->numbers;
            usort($numbers, function ($left, $right) {
                return $left['type'] === $right['type'];
            });

            $contact->numbers = $numbers;
        }

        return $contacts;
    }
}
