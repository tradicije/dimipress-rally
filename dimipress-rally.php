<?php
/**
 * Plugin Name: DimiPress Rally
 * Description: An extensible engine for table-tennis competitions, fixtures, standings, and statistics.
 * Version: 0.1.0-dev
 * Requires at least: 6.6
 * Requires PHP: 8.2
 * Author: DimiPress
 * License: AGPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/agpl-3.0.html
 * Text Domain: dimipress-rally
 * Domain Path: /languages
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

define('DIMIPRESS_RALLY_FILE', __FILE__);
define('DIMIPRESS_RALLY_PATH', plugin_dir_path(__FILE__));

$autoload = DIMIPRESS_RALLY_PATH . 'vendor/autoload.php';

if (is_readable($autoload)) {
    require_once $autoload;

    \DimiPress\Rally\WordPress\Plugin::boot();
}
