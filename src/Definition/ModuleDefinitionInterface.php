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
 * Describes everything a module registers in PrestaShop: hooks, configuration keys,
 * generated secrets, back-office tabs and where its migrations live.
 *
 * Installer, Uninstaller and Upgrade read only from this definition, so a fresh
 * install and an upgraded install always end up the same.
 */
interface ModuleDefinitionInterface
{
    /**
     * Returns the hooks the module registers on install.
     *
     * @return string[]
     */
    public function hooks(): array;

    /**
     * Returns the configuration keys with their default value.
     *
     * @return array<string, string|int|bool>
     */
    public function configuration(): array;

    /**
     * Returns the configuration keys that get a random secret on install (tokens).
     *
     * @return string[]
     */
    public function generatedConfiguration(): array;

    /**
     * Returns the back-office tabs in the format of Module::$tabs.
     *
     * @return array<int, array<string, mixed>>
     */
    public function tabs(): array;

    /**
     * Returns the directory with the install migrations, relative to the module root.
     */
    public function migrationsUpDirectory(): string;

    /**
     * Returns the directory with the uninstall migrations, relative to the module root.
     */
    public function migrationsDownDirectory(): string;
}
