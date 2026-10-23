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

namespace PrestaShopHeaven\ModuleKit\Tests\PhpStan;

use PrestaShopHeaven\ModuleKit\Definition\AbstractModuleDefinition;
use PrestaShopHeaven\ModuleKit\Definition\ModuleDefinitionInterface;
use PrestaShopHeaven\ModuleKit\ModuleBase;

/**
 * Example module that uses ModuleBase, so PHPStan analyses the trait in the context
 * of a real Module subclass. Not used at runtime.
 */
final class ExampleModule extends \Module
{
    use ModuleBase;

    /**
     * Returns an empty definition.
     */
    public function getModuleDefinition(): ModuleDefinitionInterface
    {
        return new class extends AbstractModuleDefinition {
        };
    }
}
