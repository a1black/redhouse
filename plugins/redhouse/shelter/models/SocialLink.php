<?php

declare(strict_types=1);

namespace Redhouse\Shelter\Models;

use October\Rain\Database\Model;
use System\Behaviors\SettingsModel;

/**
 * Social links settings
 */
class SocialLink extends Model
{
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
     * Checks whether social link is available.
     */
    public function isLinkEnabled(string $name): bool
    {
        return (bool) self::get($name.'_link', true);
    }
}
