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

namespace PrestaShopHeaven\ModuleKit\Tests\Unit\Updater;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PrestaShopHeaven\ModuleKit\Updater\UpdaterNotice;
use PrestaShopHeaven\ModuleKit\Updater\UpdaterNoticeTexts;

/**
 * When the "updater not installed" bar shows, what it contains, and its texts in every language.
 */
final class UpdaterNoticeTest extends TestCase
{
    private const OWN = ['AdminHeavenpricedropalertSubscriptions'];

    /**
     * @return iterable<string, array{bool, string, string, bool, string, string, bool, bool}>
     */
    public static function pages(): iterable
    {
        //            expected, module, key, updater installed, controller, configure, logged in, hidden
        yield 'configure page' => [true, 'heavenpricedropalert', '', false, 'AdminModules', 'heavenpricedropalert', true, false];
        yield 'configure page, any case' => [true, 'heavenpricedropalert', '', false, 'adminmodules', 'HeavenPriceDropAlert', true, false];
        yield 'own tab page' => [true, 'heavenpricedropalert', '', false, 'AdminHeavenpricedropalertSubscriptions', '', true, false];
        yield 'own tab page, any case' => [true, 'heavenpricedropalert', '  ', false, 'adminheavenpricedropalertsubscriptions', '', true, false];
        yield 'another module\'s configure page' => [false, 'heavenpricedropalert', '', false, 'AdminModules', 'heavenlangimages', true, false];
        yield 'module manager list' => [false, 'heavenpricedropalert', '', false, 'AdminModules', '', true, false];
        yield 'another page' => [false, 'heavenpricedropalert', '', false, 'AdminProducts', '', true, false];
        yield 'no controller' => [false, 'heavenpricedropalert', '', false, '', '', true, false];
        yield 'Addons copy (module_key)' => [false, 'heavenpricedropalert', 'a1b2c3', false, 'AdminModules', 'heavenpricedropalert', true, false];
        yield 'updater installed' => [false, 'heavenpricedropalert', '', true, 'AdminModules', 'heavenpricedropalert', true, false];
        yield 'not logged in' => [false, 'heavenpricedropalert', '', false, 'AdminModules', 'heavenpricedropalert', false, false];
        yield 'hidden for 30 days' => [false, 'heavenpricedropalert', '', false, 'AdminModules', 'heavenpricedropalert', true, true];
        yield 'the updater itself' => [false, 'heavenupdater', '', false, 'AdminModules', 'heavenupdater', true, false];
    }

    #[DataProvider('pages')]
    public function testShouldShow(bool $expected, string $module, string $key, bool $updaterInstalled, string $controller, string $configure, bool $loggedIn, bool $hidden): void
    {
        self::assertSame($expected, UpdaterNotice::shouldShow($module, $key, $updaterInstalled, $controller, $configure, self::OWN, $loggedIn, $hidden));
    }

    public function testTheNoticeNeedsOnlyItsOwnHook(): void
    {
        self::assertSame(['displayAdminAfterHeader'], UpdaterNotice::hooks());
    }

    public function testHtmlWithInstallButton(): void
    {
        $html = UpdaterNotice::html('Price Drop Alerts', UpdaterNoticeTexts::forLanguage('en'), 'https://shop.test/admin/modules/manage/action/install/heavenupdater?_token=abc&source=https%3A%2F%2Fupdates.prestahaven.com%2Fdownload%2Fheavenupdater.zip', 'https://shop.test/admin/updates');

        self::assertStringContainsString('Get one-click updates for Price Drop Alerts', $html);
        self::assertStringContainsString('data-install-url="https://shop.test/admin/modules/manage/action/install/heavenupdater?_token=abc&amp;source=https%3A%2F%2Fupdates.prestahaven.com%2Fdownload%2Fheavenupdater.zip"', $html);
        self::assertStringContainsString('data-label="Install now"', $html);
        self::assertStringContainsString('data-open-url="https://shop.test/admin/updates"', $html);
        self::assertStringContainsString('data-download-url="' . UpdaterNotice::DOWNLOAD_URL . '"', $html);
        self::assertStringContainsString('href="' . UpdaterNotice::INFO_URL . '"', $html);
        self::assertStringContainsString(UpdaterNotice::HIDE_COOKIE . '=1; max-age=2592000', $html);
        self::assertStringContainsString('<svg', $html, 'the PrestaHaven logo');
    }

    public function testHtmlWithoutTheRightToInstallHasOnlyTheLinks(): void
    {
        $html = UpdaterNotice::html('Price Drop Alerts', UpdaterNoticeTexts::forLanguage('nl'), null, 'https://shop.test/admin/updates');

        self::assertStringNotContainsString('data-install-url="', $html, 'no install button');
        self::assertStringContainsString('Updates met één klik voor Price Drop Alerts', $html);
        self::assertStringContainsString('Meer informatie', $html);
    }

    public function testHtmlEscapesEverything(): void
    {
        $html = UpdaterNotice::html('<script>alert(1)</script>', UpdaterNoticeTexts::forLanguage('en'), '"><img src=x onerror=alert(1)>', 'javascript:"x"');

        self::assertStringNotContainsString('<script>alert(1)</script>', $html);
        self::assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        self::assertStringNotContainsString('"><img', $html);
        self::assertStringContainsString('&quot;&gt;&lt;img src=x onerror=alert(1)&gt;', $html);
        self::assertStringContainsString('data-open-url="javascript:&quot;x&quot;"', $html);
    }

    public function testEveryLanguageHasEveryTextWithTheModuleName(): void
    {
        $languages = UpdaterNoticeTexts::languages();
        self::assertCount(33, $languages);
        foreach ($languages as $iso) {
            $texts = UpdaterNoticeTexts::forLanguage($iso);
            self::assertSame(['title', 'text', 'install', 'installing', 'installed', 'open', 'failed', 'download', 'info', 'hide'], array_keys($texts), $iso);
            self::assertSame(1, substr_count($texts['title'], '%s'), $iso . ': the title has the module name once');
            foreach ($texts as $key => $text) {
                self::assertNotSame('', trim($text), $iso . '.' . $key);
                if ('title' !== $key) {
                    self::assertStringNotContainsString('%', $text, $iso . '.' . $key);
                }
            }
            if ('en' !== $iso) {
                self::assertNotSame(UpdaterNoticeTexts::forLanguage('en')['text'], $texts['text'], $iso . ' is translated');
            }
        }
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function prestashopCodes(): iterable
    {
        yield 'Slovenian is si in PrestaShop' => ['si', 'sl'];
        yield 'Vietnamese is vn in PrestaShop' => ['vn', 'vi'];
        yield 'traditional Chinese falls back to Chinese' => ['tw', 'zh'];
        yield 'British English' => ['gb', 'en'];
        yield 'Brazilian Portuguese' => ['br', 'pt'];
        yield 'upper case and spaces' => [' DE ', 'de'];
    }

    #[DataProvider('prestashopCodes')]
    public function testPrestashopLanguageCodes(string $prestashop, string $iso): void
    {
        self::assertSame(UpdaterNoticeTexts::forLanguage($iso), UpdaterNoticeTexts::forLanguage($prestashop));
    }

    public function testAnUnknownLanguageFallsBackToEnglish(): void
    {
        self::assertSame(UpdaterNoticeTexts::forLanguage('en'), UpdaterNoticeTexts::forLanguage('xx'));
        self::assertSame(UpdaterNoticeTexts::forLanguage('en'), UpdaterNoticeTexts::forLanguage(''));
    }
}
