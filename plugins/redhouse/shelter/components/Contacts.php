<?php

declare(strick_types=1);

namespace Redhouse\Shelter\Components;

use October\Rain\Database\Collection;
use Cms\Classes\ComponentBase;
use Redhouse\Shelter\Models\Contact;
use Redhouse\Shelter\Models\ContactNumber;
use Redhouse\Shelter\Classes\TwigExtensions;

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
        $contacts = Contact::has('numbers')->isPublished()->orderBy('name')->get();
        foreach ($contacts as $contact) {
            foreach ($contact->numbers as $number) {
                $number->link = $this->makeCallLink($number, 'call-link');
                $number->icon = 'fab fa-'.$number->type;
            }
        }

        return $contacts;
    }

    public function makeCallLink(ContactNumber $number, string $style = null): string
    {
        switch ($number->type) {
            case ContactNumber::CN_TYPE_SKYPE:
                $link = sprintf(
                    '<a class="%s" href="skype:%s?call">%s</a>',
                    $style,
                    $number->number,
                    $number->number
                );
                break;
            case ContactNumber::CN_TYPE_VIBER:
                $link = sprintf(
                    '<a class="%s" href="viber://add?number=%%2B7%s">%s</a>',
                    $style,
                    $number->number,
                    TwigExtensions::phoneNumber($number->number)
                );
                break;
            default:
                $link = sprintf(
                    '<a class="%s" href="tel:%%2B7%s">%s</a>',
                    $style,
                    $number->number,
                    TwigExtensions::phoneNumber($number->number)
                );
        }

        return $link;
    }
}
