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

/*
 * Bootstrap for the unit tests: no PrestaShop. The classes start with an
 * _PS_VERSION_ guard, so define it here.
 */

if (!defined('_PS_VERSION_')) {
    define('_PS_VERSION_', '0.0.0-unit');
}
if (!defined('_DB_PREFIX_')) {
    define('_DB_PREFIX_', 'ps_');
}

require_once dirname(__DIR__) . '/vendor/autoload.php';
