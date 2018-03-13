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


use Illuminate\Support\Collection;
use Konekt\AppShell\Contracts\Setting;
use Konekt\AppShell\Contracts\SettingsBackend;
use Konekt\AppShell\Contracts\SettingsTab;
use Konekt\AppShell\Models\SettingScopeProxy;
use Konekt\User\Contracts\User;

class SettingsManager
{
    /**@var AvailableSettings */
    private $availableSettings;

    /** @var SettingsBackend */
    private $backend;

    /** @var Collection */
    private $tabs;

    public function __construct(AvailableSettings $availableSettings, SettingsBackend $backend)
    {
        $this->availableSettings = $availableSettings;
        $this->backend           = $backend;
        $this->tabs              = collect();
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

    /**
     * Registers a settings tab
     *
     * @param SettingsTab $tab
     */
    public function registerTab(SettingsTab $tab)
    {
        $this->tabs->put($tab->id(), $tab);
    }

    /**
     * @return Collection
     */
    public function tabs()
    {
        return $this->tabs;
    }

    /**
     * Returns the collection of available setting objects
     *
     * @return Collection
     */
    public function available()
    {
        return $this->availableSettings->all();
    }

    /**
     * Register one or more setting(s) with the system
     *
     * @param Setting|string|array    $setting
     * @param null|string|SettingsTab $tab
     * @param null                    $group
     */
    public function register($setting, $tab = null, $group = null)
    {
        $this->availableSettings->register($setting);
    }

    /**
     * Returns the collection of available application-scoped settings
     *
     * @return Collection
     */
    public function forApplication()
    {
        return $this->availableSettings->byScope(SettingScopeProxy::APPLICATION());
    }

    /**
     * Returns the collection of available user-scoped settings
     *
     * @return Collection
     */
    public function forUser()
    {
        return $this->availableSettings->byScope(SettingScopeProxy::USER());
    }
}