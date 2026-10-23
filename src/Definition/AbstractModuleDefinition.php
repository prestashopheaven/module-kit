<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 *
 * @author    George van Engers | PrestaHaven.com
 * @copyright Since 2026 George van Engers
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

namespace PrestaShopHeaven\ModuleKit\Definition;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Base for the ModuleDefinition class of a module.
 *
 * A module only overrides the constants; the methods read them with late static
 * binding (static::HOOKS), so nothing else has to be written.
 */
abstract class AbstractModuleDefinition implements ModuleDefinitionInterface
{
    /**
     * Hooks registered on install. PrestaShop unregisters them on uninstall.
     *
     * @var string[]
     */
    public const HOOKS = [];

    /**
     * Configuration keys with their default value. Prefix keys with the module name in capitals.
     *
     * @var array<string, string|int|bool>
     */
    public const CONFIGURATION = [];

    /**
     * Configuration keys that get a random secret on install (e.g. a cron token).
     *
     * @var string[]
     */
    public const GENERATED_CONFIGURATION = [];

    /**
     * Back-office tabs in the format of Module::$tabs. PrestaShop creates them on install
     * and removes them on uninstall.
     *
     * @var array<int, array<string, mixed>>
     */
    public const TABS = [];

    /** Directory with the install migrations, relative to the module root. */
    public const MIGRATIONS_UP = 'migrations/up/';

    /** Directory with the uninstall migrations, relative to the module root. */
    public const MIGRATIONS_DOWN = 'migrations/down/';

    /**
     * Returns the hooks from static::HOOKS.
     *
     * @return string[]
     */
    public function hooks(): array
    {
        return static::HOOKS;
    }

    /**
     * Returns the configuration keys and defaults from static::CONFIGURATION.
     *
     * @return array<string, string|int|bool>
     */
    public function configuration(): array
    {
        return static::CONFIGURATION;
    }

    /**
     * Returns the generated configuration keys from static::GENERATED_CONFIGURATION.
     *
     * @return string[]
     */
    public function generatedConfiguration(): array
    {
        return static::GENERATED_CONFIGURATION;
    }

    /**
     * Returns the back-office tabs from static::TABS.
     *
     * @return array<int, array<string, mixed>>
     */
    public function tabs(): array
    {
        return static::TABS;
    }

    /**
     * Returns static::MIGRATIONS_UP.
     */
    public function migrationsUpDirectory(): string
    {
        return static::MIGRATIONS_UP;
    }

    /**
     * Returns static::MIGRATIONS_DOWN.
     */
    public function migrationsDownDirectory(): string
    {
        return static::MIGRATIONS_DOWN;
    }
}
