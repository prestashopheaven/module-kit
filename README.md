# prestashopheaven/module-kit

Gedeelde basis voor alle PrestaShopHeaven-modules (PrestaShop 8.0 – 9.x, PHP 8.1+).

| Onderdeel | Wat |
|---|---|
| `ModuleBase` (trait) | `install()`, `uninstall()`, `getTabs()`, `isUsingNewTranslationSystem()` voor de hoofdklasse |
| `Definition\AbstractModuleDefinition` | Basis voor de `ModuleDefinition` van een module: alleen constanten overschrijven |
| `Install\Installer` / `Uninstaller` | Migraties, hooks, configuratie, gegenereerde secrets |
| `Install\Migrator` | SQL-migraties (`migrations/up` + `down`), engine: vanengers/prestashop-library-product-admin-helper |
| `Upgrade\Upgrade` | Helper voor `upgrade/upgrade-X.Y.Z.php` |

## Gebruik in een module

```php
// heavenfoo.php: alleen de constructor, de rest via traits
class Heavenfoo extends Module
{
    use \PrestaShopHeaven\ModuleKit\ModuleBase;
    use \PrestaShopHeaven\Foo\Module\Helper\Definition; // getModuleDefinition()

    public function __construct() { /* identiteit */ }
}
```

```php
// src/Install/ModuleDefinition.php
final class ModuleDefinition extends AbstractModuleDefinition
{
    public const HOOKS = ['displayHome'];
    public const CONFIGURATION = ['HEAVENFOO_LIMIT' => 50];
    public const GENERATED_CONFIGURATION = ['HEAVENFOO_CRON_TOKEN'];
}
```

```php
// upgrade/upgrade-1.1.0.php
function upgrade_module_1_1_0($module): bool
{
    return \PrestaShopHeaven\ModuleKit\Upgrade\Upgrade::apply($module, ['202611011200']);
}
```

`Upgrade::apply()` draait de nieuwe migraties en registreert daarna alle hooks en ontbrekende
config-sleutels uit de definitie. Een upgrade komt zo op hetzelfde uit als een verse installatie.

## Waarom een trait en geen basisklasse

De Addons-validator eist dat de hoofdklasse direct `Module`, `PaymentModule` of
`CarrierModule` uitbreidt. Een eigen basisklasse ertussen wordt afgekeurd.

## Meerdere modules in één winkel

Elke module levert zijn eigen kopie van dit package mee in `vendor/`. Draaien twee modules met
verschillende versies, dan zou PHP maar één versie laden. Daarom geeft `ps-module build`
(module-dev-tools) de namespaces van dit package en van de migratie-engine in de release-zip
een prefix per module (`extra.prestashopheaven.scope` in de composer.json van de module).

## Ontwikkelen

```bash
composer install
composer check     # lint, cs, PHPStan tegen PS 8 + 9, compat, conventies, tests
```

Lokaal gebruiken modules dit package via een path-repository (`../../libraries/*`), met
`symlink: false`. Na een wijziging: `version` in `composer.json` en `CHANGELOG.md` ophogen,
committen en taggen (`vX.Y.Z`), en in de module `composer update prestashopheaven/module-kit`.
