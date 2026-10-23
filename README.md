# prestashopheaven/module-kit

Gedeelde basis voor alle PrestaHaven-modules (PrestaShop 8.0 – 9.x, PHP 8.1+).

| Onderdeel | Wat |
|---|---|
| `ModuleBase` (trait) | `install()`, `uninstall()`, `getTabs()`, `isUsingNewTranslationSystem()` voor de hoofdklasse |
| `Definition\AbstractModuleDefinition` | Basis voor de `ModuleDefinition` van een module: alleen constanten overschrijven |
| `Install\Installer` / `Uninstaller` | Migraties, hooks, configuratie, gegenereerde secrets |
| `Install\Migrator` | SQL-migraties (`migrations/up` + `down`), engine: vanengers/prestashop-library-product-admin-helper |
| `Upgrade\Upgrade` | Helper voor `upgrade/upgrade-X.Y.Z.php` |
| `Shop\ShopScope` | Multistore: `runInAllShops()`, `isModuleActiveInShop()`, `shopIds()` |

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

## Multistore

Install, uninstall en upgrade draaien in de context "alle winkels" (`ShopScope::runInAllShops()`):
de module staat in elke winkel aan en standaardwaarden en tokens zijn globaal, ook als de module
vanuit één winkel of via `bin/console` wordt geïnstalleerd. Een upgrade overschrijft nooit waarden die
de winkelier per winkel of groep heeft gezet.

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

Modules halen dit package van Packagist. Wil je een wijziging meteen in een module proberen:
`vendor/bin/ps-module link` in die module (daarna `ps-module unlink`).

## Release

1. `version` in `composer.json` en `CHANGELOG.md` ophogen.
2. Commit met het bericht `Release X.Y.Z - <omschrijving>` en push naar `main`.
3. De release-workflow controleert de versie, draait alle checks (ook PHPStan tegen PS 8 en 9),
   maakt tag `vX.Y.Z` met een GitHub-release en werkt Packagist bij.
4. In de modules: `composer update prestashopheaven/module-kit`.
