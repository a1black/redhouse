<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use October\Rain\Database\Model;
use October\Rain\Database\Traits\Validation;
use System\Behaviors\SettingsModel;

/**
 * Social links settings
 */
class SocialLink extends Model
{
    use Validation;

    /**
     * Unique identifier under which settings are stored.
     *
     * @var string
     */
    public $settingsCode = 'redhouse_shelter_sociallinks';

    /** @var array */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * Form fields definitions.
     *
     * @var string
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Validation rules.
     *
     * @var array
     */
    public $rules = [
        'fb_link' => 'url',
        'vk_link' => 'url',
        'odnoklassniki_link' => 'url',
        'google_link' => 'url',
    ];

    /**
     * Custom validation messages
     *
     * @var array
     */
    public $customMessages = [
        'url' => 'redhouse.shelter::lang.social.error.url',
    ];

    /**
     * Checks whether social link is available.
     */
    public function isLinkEnabled(string $name): bool
    {
        return (bool) self::get($name.'_link', true);
    }
}
