<?php
/**
 * HC Apps Helper Functions
 *
 * @package HoytCreativeApps
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Helper class for HC Apps functionality
 */
class HC_Apps_Helper
{
    /**
     * Get all apps
     *
     * @param array $args WP_Query arguments
     * @return WP_Query
     */
    public static function get_apps($args = [])
    {
        $defaults = [
            'post_type' => 'hc-apps',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ];

        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    /**
     * Get featured apps
     *
     * @param int $limit Number of apps to return
     * @return WP_Query
     */
    public static function get_featured_apps($limit = 6)
    {
        return self::get_apps([
            'meta_query' => [
                [
                    'key' => '_hc_app_featured',
                    'value' => '1',
                    'compare' => '=',
                ],
            ],
            'posts_per_page' => $limit,
        ]);
    }

    /**
     * Get apps by platform
     *
     * @param string $platform Platform slug
     * @param int $limit Number of apps to return
     * @return WP_Query
     */
    public static function get_apps_by_platform($platform, $limit = -1)
    {
        return self::get_apps([
            'meta_query' => [
                [
                    'key' => '_hc_app_platform',
                    'value' => $platform,
                    'compare' => '=',
                ],
            ],
            'posts_per_page' => $limit,
        ]);
    }

    /**
     * Get apps by status
     *
     * @param string $status Status slug
     * @param int $limit Number of apps to return
     * @return WP_Query
     */
    public static function get_apps_by_status($status, $limit = -1)
    {
        return self::get_apps([
            'meta_query' => [
                [
                    'key' => '_hc_app_status',
                    'value' => $status,
                    'compare' => '=',
                ],
            ],
            'posts_per_page' => $limit,
        ]);
    }

    /**
     * Get app meta data
     *
     * @param int $app_id App post ID
     * @return array
     */
    public static function get_app_meta($app_id)
    {
        return [
            'version' => get_post_meta($app_id, '_hc_app_version', true),
            'developer' => get_post_meta($app_id, '_hc_app_developer', true),
            'platform' => get_post_meta($app_id, '_hc_app_platform', true),
            'status' => get_post_meta($app_id, '_hc_app_status', true),
            'featured' => get_post_meta($app_id, '_hc_app_featured', true),
            'demo_url' => get_post_meta($app_id, '_hc_app_demo_url', true),
            'download_url' => get_post_meta($app_id, '_hc_app_download_url', true),
            'github_url' => get_post_meta($app_id, '_hc_app_github_url', true),
            'docs_url' => get_post_meta($app_id, '_hc_app_docs_url', true),
        ];
    }

    /**
     * Get platform display name
     *
     * @param string $platform Platform slug
     * @return string
     */
    public static function get_platform_name($platform)
    {
        $platforms = [
            'web' => __('Web', 'hoytcreative-apps'),
            'ios' => __('iOS', 'hoytcreative-apps'),
            'android' => __('Android', 'hoytcreative-apps'),
            'desktop' => __('Desktop', 'hoytcreative-apps'),
            'cross-platform' => __('Cross-Platform', 'hoytcreative-apps'),
        ];

        return isset($platforms[$platform]) ? $platforms[$platform] : $platform;
    }

    /**
     * Get status display name
     *
     * @param string $status Status slug
     * @return string
     */
    public static function get_status_name($status)
    {
        $statuses = [
            'active' => __('Active', 'hoytcreative-apps'),
            'development' => __('In Development', 'hoytcreative-apps'),
            'deprecated' => __('Deprecated', 'hoytcreative-apps'),
            'discontinued' => __('Discontinued', 'hoytcreative-apps'),
        ];

        return isset($statuses[$status]) ? $statuses[$status] : $status;
    }

    /**
     * Get app categories
     *
     * @param int $app_id App post ID
     * @return array
     */
    public static function get_app_categories($app_id)
    {
        return wp_get_post_terms($app_id, 'hc-app-category', ['fields' => 'all']);
    }

    /**
     * Get app tags
     *
     * @param int $app_id App post ID
     * @return array
     */
    public static function get_app_tags($app_id)
    {
        return wp_get_post_terms($app_id, 'hc-app-tag', ['fields' => 'all']);
    }

    /**
     * Check if app is featured
     *
     * @param int $app_id App post ID
     * @return bool
     */
    public static function is_featured($app_id)
    {
        return get_post_meta($app_id, '_hc_app_featured', true) === '1';
    }

    /**
     * Get app status badge HTML
     *
     * @param string $status Status slug
     * @return string
     */
    public static function get_status_badge($status)
    {
        $badges = [
            'active' => '<span class="hc-app-status active">●</span>',
            'development' => '<span class="hc-app-status development">●</span>',
            'deprecated' => '<span class="hc-app-status deprecated">●</span>',
            'discontinued' => '<span class="hc-app-status discontinued">●</span>',
        ];

        return isset($badges[$status]) ? $badges[$status] : '';
    }
}