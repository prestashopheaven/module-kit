# Changelog

## [1.3.0] - 2026-10-25

### Toegevoegd
- Updater-melding (`Updater\UpdaterNotice`, `Updater\UpdaterNoticeTexts`): een balk op de eigen backoffice-pagina's
  van de module wanneer de gratis PrestaHaven Updater niet geïnstalleerd is, alleen voor directe kopieën (lege
  `module_key`). "Nu installeren" gebruikt de Module Manager-route van PrestaShop (rechten en demomodus van PrestaShop
  zelf), plus "Meer informatie" en "30 dagen verbergen". Teksten in de 33 talen van de modules; geen netwerkverkeer.
- `ModuleBase::hookDisplayAdminAfterHeader()` toont de melding; de Installer registreert de hook alleen als de melding
  in de build zit (de Addons-build van `ps-module build` laat `src/Updater` weg). Een module met een eigen
  `hookDisplayAdminAfterHeader` lost de botsing op met `insteadof ModuleBase` (UPD-03).

## [1.2.1] - 2026-10-23

### Gewijzigd
- Onderhoud: bijgewerkte package-metadata en bestandsheaders.

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
