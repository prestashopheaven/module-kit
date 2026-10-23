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

namespace PrestaShopHeaven\ModuleKit\Tests\Unit\Definition;

use PHPUnit\Framework\TestCase;
use PrestaShopHeaven\ModuleKit\Definition\AbstractModuleDefinition;

/**
 * A module definition only overrides constants; the base methods must return them.
 */
final class AbstractModuleDefinitionTest extends TestCase
{
    /**
     * An empty definition returns empty lists and the default migration directories.
     */
    public function testDefaults(): void
    {
        $definition = new class extends AbstractModuleDefinition {
        };

        self::assertSame([], $definition->hooks());
        self::assertSame([], $definition->configuration());
        self::assertSame([], $definition->generatedConfiguration());
        self::assertSame([], $definition->tabs());
        self::assertSame('migrations/up/', $definition->migrationsUpDirectory());
        self::assertSame('migrations/down/', $definition->migrationsDownDirectory());
    }

    /**
     * Constants overridden in the module's definition are returned (late static binding).
     */
    public function testOverriddenConstantsAreReturned(): void
    {
        $definition = new class extends AbstractModuleDefinition {
            public const HOOKS = ['displayHome', 'actionCartSave'];
            public const CONFIGURATION = ['MYMODULE_LIMIT' => 10];
            public const GENERATED_CONFIGURATION = ['MYMODULE_TOKEN'];
            public const TABS = [['class_name' => 'AdminMyModule', 'name' => 'My module']];
            public const MIGRATIONS_UP = 'sql/up/';
        };

        self::assertSame(['displayHome', 'actionCartSave'], $definition->hooks());
        self::assertSame(['MYMODULE_LIMIT' => 10], $definition->configuration());
        self::assertSame(['MYMODULE_TOKEN'], $definition->generatedConfiguration());
        self::assertSame([['class_name' => 'AdminMyModule', 'name' => 'My module']], $definition->tabs());
        self::assertSame('sql/up/', $definition->migrationsUpDirectory());
    }
}
