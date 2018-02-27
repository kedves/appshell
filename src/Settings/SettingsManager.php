<?php
/**
 * Contains the SettingsManager class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-02-26
 *
 */


namespace Konekt\AppShell\Settings;


use Konekt\AppShell\Contracts\Setting;
use Konekt\AppShell\Contracts\SettingsBackend;
use Konekt\User\Contracts\User;

class SettingsManager
{
    /**
     * @var AvailableSettings
     */
    private $availableSettings;

    /**
     * @var SettingsBackend
     */
    private $backend;

    public function __construct(AvailableSettings $availableSettings, SettingsBackend $backend)
    {
        $this->availableSettings = $availableSettings;
        $this->backend           = $backend;
    }

    /**
     * Returns the value for a specific setting
     *
     * @param Setting|string $setting
     * @param null|User|int  $user
     *
     * @return mixed
     */
    public function get($setting, $user = null)
    {
        $result = $this->backend->get($setting, $user);

        // Optimal case, we have a result from backend
        if (!is_null($result)) {
            return $result;
        }

        // Return default from the setting
        if ($setting instanceof Setting) {
            return $setting->default();
        }

        // We have received a setting key, so need to fetch the object
        $setting = $this->availableSettings->getByKey($setting);

        return $setting ? $setting->default() : null;
    }

}
