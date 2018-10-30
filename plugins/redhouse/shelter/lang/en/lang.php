<?php

return [
    'plugin' => [
        'name' => 'Animal Shelter',
        'description' => 'Module for managing catalog of animals placed in a shelter.',
    ],
    'general' => [
        'change' => 'Change',
        'warning' => 'Warning',
        'error' => 'Error',
    ],
    'nav' => [
        'menu' => [
            'label' => 'Shelter',
            'description' => 'Manage catalog of animals in a shelter',
        ],
        'cashaccounts' => [
            'label' => 'Money Accounts',
            'description' => 'Manage money accounts for receiving donations',
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
        'return_to_cash' => 'Back to Cash Accounts',
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
                'name' => 'Contact Name',
                'number' => 'Contact Number',
                'published' => 'Visible',
            ],
        ],
        'contact_number' => [
            'form' => 'Contact Numbers',
            'list' => 'List of Contact Numbers',
            'empty_msg' => 'No contact numbers',
        ],
        'taxinfo' => [
            'form' => 'Tax Details',
            'create' => 'Add Tax Information',
            'update' => 'Change Tax Information',
            'update_msg' => 'Information was updated',
            'empty_msg' => 'No tax details found',
        ],
        'cashaccount' => [
            'form' => 'Bank account',
            'list' => 'List of bank accounts',
            'create' => 'Add Account',
            'update' => 'Change Account',
            'preview' => 'View Account details',
            'delete' => 'Delete Account',
            'create_msg' => 'Account has been created',
            'update_msg' => 'Account has been updated',
            'delete_msg' => 'Account has been deleted',
            'delete_confirm' => 'Do you want to delete account?',
            'empty_msg' => 'No bank accounts found',
            'search_msg' => 'Search for a account details',
            'filter' => [
                'bank' => 'Bank name',
                'type' => 'Account type',
                'account' => 'Account number',
            ],
        ],
    ],
    'social' => [
        'model_title' => 'Social Media',
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
        'error' => [
            'url' => 'Link to :attribute page is not valid',
        ],
    ],
    'contact' => [
        'model_title' => 'Company Contact details',
        'name_label' => 'Name',
        'name_desc' => 'Persion responding to call',
        'note_label' => 'Note',
        'note_desc' => 'Short note for caller',
        'description_label' => 'Description',
        'description_desc' => 'Short info to distinguish contacts',
        'published_label' => 'Visible',
        'published_desc' => 'Show on contact page',
        'number_count_label' => 'Contact Count',
        'error' => [
            'name' => 'Invalid contact name',
        ],
    ],
    'contact_number' => [
        'model_title' => 'Contact number',
        'type_label' => 'Operator Type',
        'number_label' => 'Contact Number',
        'enabled_label' => 'Contact State',
        'type' => [
            'mobil' => 'Mobile Operator',
            'skype' => 'Skype',
            'viber' => 'Viber',
        ],
        'error' => [
            'number_digits' => 'Phone number must be eleven digits long',
            'number_alphanum' => 'Account name is not valid',
        ],
    ],
    'taxinfo' => [
        'model_title' => 'Organization tax identifications',
        'tax_id_label' => 'Tax ID',
        'tax_id_desc' => 'Unique nuber issed to your organization',
        'tax_number_label' => 'Tax Number',
        'tax_number_desc' => 'Number by which your organization is registreg in a tax collecting institution',
        'fullname_label' => 'Organization name',
        'fullname_desc' => 'Name under wich you organization is registred',
        'purpose_label' => 'Transaction message',
        'purpose_desc' => 'Message provided during money transaction',
        'error' => [
            'tax_id' => 'The :attribute must be 10 or 12 digit long',
            'tax_number' => 'The :attribute must be :digits digit long',
        ],
    ],
    'cashaccount' => [
        'model_title' => 'Money account details',
        'type_label' => 'Account Type',
        'bank_name_label' => 'Bank',
        'bank_name_desc' => 'Name of your bank',
        'bank_id_code_label' => 'Bank ID',
        'bank_id_code_desc' => 'Number of a license issued to your bank',
        'account_label' => 'Account Number',
        'correspondent_label' => 'Correspondent Account',
        'correspondent_desc' => 'Number of your bank correspondent account',
        'transfer_url' => 'Transfer Link',
        'embedded_html' => 'HTML Widget',
        'type' => [
            'bank' => 'Bank',
            'yandex' => 'Yandex.Wallet',
            'paypal' => 'PayPal Wallet',
        ],
        'error' => [
            'bank' => 'Invalid name of a bank',
        ]
    ],
];
