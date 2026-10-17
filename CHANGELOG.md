# Changelog

## [1.2.0] - 2026-10-17

### Toegevoegd
- `Shop\ShopScope`: `runInAllShops()`, `isModuleActiveInShop()`, `shopIds()` (multistore, MS-02/09/11).

### Gewijzigd
- Install, uninstall en `Upgrade::apply()` draaien in de context "alle winkels": de module staat aan in
  elke winkel, ook bij installeren vanuit één winkel of `bin/console` (MS-02).
- Standaardwaarden en gegenereerde tokens zijn globaal (`updateGlobalValue`); een upgrade overschrijft
  nooit winkel- of groepswaarden; een verse install ruimt oude winkelwaarden op (MS-03).

## [1.1.0] - 2026-10-17

### Toegevoegd
- `Installer::runAllMigrations()`; `runMigrations([])` doet niets meer (geen per ongeluk opnieuw
  draaien van alle migraties in een upgrade).
- CI- en release-workflows: checks (PHPStan tegen PS 8.1.0 en 9.2.0), tag, GitHub-release, Packagist.

### Gewijzigd
- Publiek op github.com/prestashopheaven/module-kit en Packagist; dev-tools via Packagist.

## [1.0.0] - 2026-10-17

### Toegevoegd
- Eerste versie.
