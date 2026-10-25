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

namespace PrestaShopHeaven\ModuleKit\Updater;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * The passive "PrestaHaven Updater not installed" bar (update-system/PLAN.md §6). It only reads local state: no network
 * call, no dependency, and the module works the same without it. Shown on the module's own back-office pages, only for
 * direct-sale copies (empty module_key) when heavenupdater is not installed. "Install now" posts to PrestaShop's own
 * Module Manager route (install from the public zip URL), so PrestaShop checks the employee's rights and demo mode.
 *
 * The Addons build leaves this folder out (ps-module build --channel=addons): then the hook is not registered and
 * ModuleBase::hookDisplayAdminAfterHeader() returns ''.
 */
final class UpdaterNotice
{
    public const HOOK = 'displayAdminAfterHeader';
    public const UPDATER_MODULE = 'heavenupdater';
    public const UPDATER_PAGE = 'AdminHeavenupdaterUpdates';
    public const DOWNLOAD_URL = 'https://updates.prestahaven.com/download/heavenupdater.zip';
    public const INFO_URL = 'https://updates.prestahaven.com/';
    /** Set by "Hide for 30 days" (browser cookie, all PrestaHaven modules). */
    public const HIDE_COOKIE = 'prestahaven_updater_notice_hidden';

    private const LOGO = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="34" height="34" aria-hidden="true"><polygon points="62,105 193,0 336,72 205,182" fill="#802982"/><polygon points="62,105 205,182 205,512 62,435" fill="#E31B87"/><polygon points="256,203 350,126 452,178 358,256" fill="#802982"/><polygon points="256,203 358,256 358,512 256,460" fill="#E31B87"/></svg>';

    /**
     * The hooks the notice needs (registered by the Installer next to the module's own hooks).
     *
     * @return list<string>
     */
    public static function hooks(): array
    {
        return [self::HOOK];
    }

    /**
     * Whether the bar belongs on this page.
     *
     * @param string $controller the current back-office controller (legacy name; Symfony pages give their _legacy_controller)
     * @param string $configure the "configure" request parameter (module configuration page)
     * @param list<string> $ownControllers the module's own admin controllers (its tabs' class names)
     */
    public static function shouldShow(
        string $moduleName,
        string $moduleKey,
        bool $updaterInstalled,
        string $controller,
        string $configure,
        array $ownControllers,
        bool $employeeLoggedIn,
        bool $hidden,
    ): bool {
        if ('' !== trim($moduleKey) || $updaterInstalled || !$employeeLoggedIn || $hidden || self::UPDATER_MODULE === $moduleName) {
            return false;
        }
        $onConfigurePage = 0 === strcasecmp($controller, 'AdminModules') && 0 === strcasecmp($configure, $moduleName);
        $onOwnPage = '' !== $controller && \in_array(strtolower($controller), array_map('strtolower', $ownControllers), true);

        return $onConfigurePage || $onOwnPage;
    }

    /**
     * The bar's HTML (escaped). Without an install URL (no right to install modules) only the links are shown.
     *
     * @param array<string, string> $texts UpdaterNoticeTexts::forLanguage()
     */
    public static function html(string $moduleDisplayName, array $texts, ?string $installUrl, string $updaterPageUrl): string
    {
        $e = static fn (string $value): string => htmlspecialchars($value, \ENT_QUOTES | \ENT_SUBSTITUTE, 'UTF-8');
        $id = 'prestahaven-updater-notice';
        $install = null === $installUrl ? '' : \sprintf(
            '<button type="button" class="btn btn-primary" data-label="%s" data-install-url="%s" data-installing="%s" data-installed="%s" data-open="%s" data-open-url="%s" data-failed="%s" data-download="%s" data-download-url="%s" style="background:#E31B87;border-color:#E31B87">%s</button> ',
            $e($texts['install']),
            $e($installUrl),
            $e($texts['installing']),
            $e($texts['installed']),
            $e($texts['open']),
            $e($updaterPageUrl),
            $e($texts['failed']),
            $e($texts['download']),
            $e(self::DOWNLOAD_URL),
            $e($texts['install']),
        );

        return '<div id="' . $id . '" class="alert alert-info" role="status" style="display:flex;gap:14px;align-items:center;border-left:4px solid #E31B87;background:#fdf2f8;color:#3C3C3B;margin:10px 0">'
            . self::LOGO
            . '<div style="flex:1"><strong>' . $e(\sprintf($texts['title'], $moduleDisplayName)) . '</strong><br>' . $e($texts['text'])
            . '<div class="prestahaven-updater-actions" style="margin-top:8px">' . $install
            . '<a class="btn btn-default" href="' . $e(self::INFO_URL) . '" target="_blank" rel="noopener">' . $e($texts['info']) . '</a> '
            . '<a href="#" class="prestahaven-updater-hide" style="margin-left:8px">' . $e($texts['hide']) . '</a>'
            . '<span class="prestahaven-updater-result" style="margin-left:8px"></span></div></div></div>'
            . self::script($id);
    }

    /** The bar's small script: install through PrestaShop's Module Manager route, or hide for 30 days. */
    private static function script(string $id): string
    {
        $cookie = self::HIDE_COOKIE;

        return <<<HTML
            <script>
            (function () {
              var bar = document.getElementById('{$id}');
              if (!bar) { return; }
              var result = bar.querySelector('.prestahaven-updater-result');
              var show = function (text, href, label) {
                result.textContent = text + ' ';
                if (href) { var a = document.createElement('a'); a.href = href; a.textContent = label; result.appendChild(a); }
              };
              bar.querySelector('.prestahaven-updater-hide').addEventListener('click', function (event) {
                event.preventDefault();
                document.cookie = '{$cookie}=1; max-age=2592000; path=/; SameSite=Lax';
                bar.parentNode.removeChild(bar);
              });
              var button = bar.querySelector('[data-install-url]');
              if (!button) { return; }
              button.addEventListener('click', function () {
                var d = button.dataset;
                button.disabled = true;
                button.textContent = d.installing;
                fetch(d.installUrl, { method: 'POST', credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                  .then(function (response) { return response.json(); })
                  .then(function (json) {
                    var answer = json && json.heavenupdater ? json.heavenupdater : json;
                    if (answer && answer.status === true) {
                      button.style.display = 'none';
                      show(d.installed, d.openUrl, d.open);
                    } else {
                      throw new Error(answer && answer.msg ? answer.msg : '');
                    }
                  })
                  .catch(function (error) {
                    button.disabled = false;
                    button.textContent = d.label;
                    show(d.failed + ' ' + (error && error.message ? error.message : ''), d.downloadUrl, d.download);
                  });
              });
            })();
            </script>
            HTML;
    }

    /**
     * Renders the bar for a module on the current back-office page, or '' (also on any error: it never breaks a page).
     *
     * @param list<string> $ownControllers the module's admin controllers (its tabs' class names)
     */
    public static function render(\Module $module, array $ownControllers): string
    {
        try {
            $context = \Context::getContext();
            if (null === $context || !$context->employee instanceof \Employee || !$context->employee->isLoggedBack()) {
                return '';
            }
            $controller = $context->controller instanceof \Controller && \is_string($context->controller->controller_name ?? null)
                ? $context->controller->controller_name
                : (string) \Tools::getValue('controller');
            $moduleKey = $module->module_key;
            if (!self::shouldShow(
                (string) $module->name,
                $moduleKey,
                (bool) \Module::isInstalled(self::UPDATER_MODULE),
                $controller,
                (string) \Tools::getValue('configure'),
                $ownControllers,
                true,
                isset($_COOKIE[self::HIDE_COOKIE]),
            )) {
                return '';
            }
            $link = $context->link;
            $installUrl = null;
            if (\Access::isGranted('ROLE_MOD_TAB_ADMINMODULESSF_CREATE', (int) $context->employee->id_profile)) {
                $url = $link->getAdminLink('AdminModulesSf', true, ['route' => 'admin_module_manage_action', 'action' => 'install', 'module_name' => self::UPDATER_MODULE]);
                $installUrl = $url . (str_contains($url, '?') ? '&' : '?') . 'source=' . rawurlencode(self::DOWNLOAD_URL);
            }
            $language = $context->language instanceof \Language ? (string) $context->language->iso_code : 'en';

            return self::html((string) $module->displayName, UpdaterNoticeTexts::forLanguage($language), $installUrl, $link->getAdminLink(self::UPDATER_PAGE));
        } catch (\Throwable) {
            return '';
        }
    }
}
