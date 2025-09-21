<?php
/**
 * Plugin Name: Hoyt Creative Apps
 * Plugin URI: https://github.com/nathanielhoyt/hoytcreative-apps
 * Description: A plugin to display and manage various apps for Hoyt Creative
 * Version: 1.0.0
 * Author: Nathaniel Hoyt
 * Author URI: https://hoytcreative.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: hoytcreative-apps
 * Domain Path: /languages
 *
 * @package HoytCreativeApps
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('HOYTCREATIVE_APPS_VERSION', '1.0.0');
define('HOYTCREATIVE_APPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HOYTCREATIVE_APPS_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class HoytCreativeApps
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('init', [$this, 'init']);
        add_action('plugins_loaded', [$this, 'load_textdomain']);
        
        // Include required files
        $this->include_files();
    }

    /**
     * Include required files
     */
    private function include_files()
    {
        require_once HOYTCREATIVE_APPS_PLUGIN_DIR . 'includes/class-hc-apps-helper.php';
        require_once HOYTCREATIVE_APPS_PLUGIN_DIR . 'includes/post-types/class-hc-apps.php';
    }

    /**
     * Initialize the plugin
     */
    public function init()
    {
        // Plugin is now initialized through included files
        // Post types and taxonomies are registered automatically
    }

    /**
     * Load plugin textdomain
     */
    public function load_textdomain()
    {
        load_plugin_textdomain(
            'hoytcreative-apps',
            false,
            dirname(plugin_basename(__FILE__)) . '/languages/'
        );
    }

    /**
     * Plugin activation hook
     */
    public static function activate()
    {
        // TODO: Add activation logic
        // - Create database tables if needed
        // - Set default options
        // - Flush rewrite rules
    }

    /**
     * Plugin deactivation hook
     */
    public static function deactivate()
    {
        // TODO: Add deactivation logic
        // - Clean up temporary data
        // - Flush rewrite rules
    }
}

// Initialize the plugin
new HoytCreativeApps();

// Register activation and deactivation hooks
register_activation_hook(__FILE__, ['HoytCreativeApps', 'activate']);
register_deactivation_hook(__FILE__, ['HoytCreativeApps', 'deactivate']);