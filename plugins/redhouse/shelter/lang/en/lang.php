<?php
return [
    'plugin' => [
        'name' => 'Animal Shelter',
        'description' => 'Module for managing catalog of animals placed in a shelter.',
    ],
    'nav' => [
        'menu' => [
            'label' => 'Shelter',
            'description' => 'Manage catalog of animals in a shelter',
        ],
        'social' => [
            'label' => 'Social Links',
            'description' => 'Manage organization featured social networks',
        ],
        'contacts' => [
            'label' => 'Contact List',
            'description' => 'Manage organization contact information',
        ],
        'settings' => [
            'label' => 'Settings',
            'description' => 'Manage various options'
        ],
    ],
    'redirect' => [
        'return_to_main' => 'Back to Main',
        'return_to_contacts' => 'Back to Contacts',
    ],
    'form' => [
        'create_and_new' => 'Save and Add',
    ],
    'view' => [
        'social' => [
            'form' => 'Social Networks',
            'update_msg' => 'Links has been updated',
        ],
        'contact' => [
            'form' => 'Contact',
            'list' => 'List of Contacts',
            'create' => 'Add Contact',
            'update' => 'Change Contact',
            'preview' => 'View Contact',
            'delete' => 'Delete Contact',
            'create_msg' => 'Contact has been added',
            'update_msg' => 'Contact has been updated',
            'delete_msg' => 'Contact has been deleted',
            'delete_confirm' => 'Do you want to delete contact?',
            'empty_msg' => 'No contacts found',
            'search_msg' => 'Search for a contact',
            'filter' => [
                'name' => 'Name',
                'published' => 'Visible',
            ],
        ],
        'contact_number' => [
            'form' => 'Contact Numbers',
            'list' => 'List of Contact Numbers',
            'empty_msg' => 'No contact numbers',
        ],
    ],
    'social' => [
        'head_section_label' => 'Social Networks',
        'head_section_desc' => 'Manage connections to social networks',
        'toggle_section_label' => 'Link control',
        'toggle_section_desc' => 'Manage social links visibility',
        'fb_label' => 'Facebook',
        'fb_desc' => 'Facebook page URL',
        'vk_label' => 'VKontakte',
        'vk_desc' => 'VK group URL',
        'odnoklassniki_label' => 'Odnoklassniki',
        'odnoklassniki_desc' => 'Odnoklassniki page URL',
        'google_label' => 'Google+',
        'google_desc' => 'Google Plus page URL',
    ],
    'contact' => [
        'model_title' => 'Company Contact details',
        'name_label' => 'Contact',
        'name_desc' => 'Persion responding to call',
        'note_label' => 'Note',
        'note_desc' => 'Short note for caller',
        'description_label' => 'Description',
        'description_desc' => 'Short info to distinguish contacts',
        'published_label' => 'Show on contact page',
        'published_switch' => [
            'on' => 'Contact displayed',
            'off' => 'Contact hidden',
        ],
        'number_count_label' => 'Contact Count',
    ],
    'contact_number' => [
        'model_title' => 'Contact number',
        'type_label' => 'Operator type',
        'number_label' => 'Contact number',
        'enabled_label' => 'Contact state',
        'enabled_switch' => [
            'on' => 'Active',
            'off' => 'Disabled',
        ],
        'type' => [
            'mobil' => 'Mobile Operator',
            'skype' => 'Skype',
            'viber' => 'Viber',
        ],
    ],
];
