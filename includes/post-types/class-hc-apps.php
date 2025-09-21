<?php

/**
 * HC Apps Custom Post Type
 *
 * @package HoytCreativeApps
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * HC Apps post type class
 */
class HC_Apps_Post_Type
{
    /**
     * Post type slug
     */
    const POST_TYPE = 'hc-apps';

    /**
     * Post type name
     */
    const POST_TYPE_NAME = 'HoytCreative App';
    const POST_TYPE_NAME_PLURAL = 'HoytCreative Apps';

    /**
     * Taxonomy slug for app categories
     */
    const TAXONOMY_CATEGORY = 'hc-app-category';

    /**
     * Taxonomy slug for app tags
     */
    const TAXONOMY_TAG = 'hc-app-tag';

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomies']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        add_filter('manage_' . self::POST_TYPE . '_posts_columns', [$this, 'admin_columns']);
        add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [$this, 'admin_column_content'], 10, 2);
    }

    /**
     * Register the custom post type
     */
    public function register_post_type()
    {
        $labels = [
            'name'                  => _x(self::POST_TYPE_NAME_PLURAL, 'Post type general name', 'hoytcreative-apps'),
            'singular_name'         => _x(self::POST_TYPE_NAME, 'Post type singular name', 'hoytcreative-apps'),
            'menu_name'             => _x(self::POST_TYPE_NAME_PLURAL, 'Admin Menu text', 'hoytcreative-apps'),
            'name_admin_bar'        => _x(self::POST_TYPE_NAME, 'Add New on Toolbar', 'hoytcreative-apps'),
            'add_new'               => __('Add New', 'hoytcreative-apps'),
            'add_new_item'          => sprintf(__('Add New %s', 'hoytcreative-apps'), self::POST_TYPE_NAME),
            'new_item'              => sprintf(__('New %s', 'hoytcreative-apps'), self::POST_TYPE_NAME),
            'edit_item'             => sprintf(__('Edit %s', 'hoytcreative-apps'), self::POST_TYPE_NAME),
            'view_item'             => sprintf(__('View %s', 'hoytcreative-apps'), self::POST_TYPE_NAME),
            'all_items'             => sprintf(__('All %s', 'hoytcreative-apps'), self::POST_TYPE_NAME_PLURAL),
            'search_items'          => sprintf(__('Search %s', 'hoytcreative-apps'), self::POST_TYPE_NAME_PLURAL),
            'parent_item_colon'     => sprintf(__('Parent %s:', 'hoytcreative-apps'), self::POST_TYPE_NAME_PLURAL),
            'not_found'             => sprintf(__('No %s found.', 'hoytcreative-apps'), strtolower(self::POST_TYPE_NAME_PLURAL)),
            'not_found_in_trash'    => sprintf(__('No %s found in Trash.', 'hoytcreative-apps'), strtolower(self::POST_TYPE_NAME_PLURAL)),
            'featured_image'        => sprintf(_x('%s Cover Image', 'Overrides the "Featured Image" phrase', 'hoytcreative-apps'), self::POST_TYPE_NAME),
            'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase', 'hoytcreative-apps'),
            'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase', 'hoytcreative-apps'),
            'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase', 'hoytcreative-apps'),
            'archives'              => sprintf(_x('%s archives', 'The post type archive label', 'hoytcreative-apps'), self::POST_TYPE_NAME),
            'insert_into_item'      => sprintf(_x('Insert into %s', 'Overrides the "Insert into post" phrase', 'hoytcreative-apps'), strtolower(self::POST_TYPE_NAME)),
            'uploaded_to_this_item' => sprintf(_x('Uploaded to this %s', 'Overrides the "Uploaded to this post" phrase', 'hoytcreative-apps'), strtolower(self::POST_TYPE_NAME)),
            'filter_items_list'     => sprintf(_x('Filter %s list', 'Screen reader text for the filter links', 'hoytcreative-apps'), strtolower(self::POST_TYPE_NAME_PLURAL)),
            'items_list_navigation' => sprintf(_x('%s list navigation', 'Screen reader text for the pagination', 'hoytcreative-apps'), self::POST_TYPE_NAME_PLURAL),
            'items_list'            => sprintf(_x('%s list', 'Screen reader text for the items list', 'hoytcreative-apps'), self::POST_TYPE_NAME_PLURAL),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true, // Enable Gutenberg and REST API
            'query_var'          => true,
            'rewrite'            => ['slug' => 'apps'],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-admin-tools',
            'supports'           => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions'],
            'taxonomies'         => [self::TAXONOMY_CATEGORY, self::TAXONOMY_TAG],
        ];

        register_post_type(self::POST_TYPE, $args);
    }

    /**
     * Register taxonomies for the post type
     */
    public function register_taxonomies()
    {
        // App Categories
        $category_labels = [
            'name'              => _x('App Categories', 'taxonomy general name', 'hoytcreative-apps'),
            'singular_name'     => _x('App Category', 'taxonomy singular name', 'hoytcreative-apps'),
            'search_items'      => __('Search App Categories', 'hoytcreative-apps'),
            'all_items'         => __('All App Categories', 'hoytcreative-apps'),
            'parent_item'       => __('Parent App Category', 'hoytcreative-apps'),
            'parent_item_colon' => __('Parent App Category:', 'hoytcreative-apps'),
            'edit_item'         => __('Edit App Category', 'hoytcreative-apps'),
            'update_item'       => __('Update App Category', 'hoytcreative-apps'),
            'add_new_item'      => __('Add New App Category', 'hoytcreative-apps'),
            'new_item_name'     => __('New App Category Name', 'hoytcreative-apps'),
            'menu_name'         => __('Categories', 'hoytcreative-apps'),
        ];

        register_taxonomy(self::TAXONOMY_CATEGORY, [self::POST_TYPE], [
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => 'app-category'],
        ]);

        // App Tags
        $tag_labels = [
            'name'                       => _x('App Tags', 'taxonomy general name', 'hoytcreative-apps'),
            'singular_name'              => _x('App Tag', 'taxonomy singular name', 'hoytcreative-apps'),
            'search_items'               => __('Search App Tags', 'hoytcreative-apps'),
            'popular_items'              => __('Popular App Tags', 'hoytcreative-apps'),
            'all_items'                  => __('All App Tags', 'hoytcreative-apps'),
            'edit_item'                  => __('Edit App Tag', 'hoytcreative-apps'),
            'update_item'                => __('Update App Tag', 'hoytcreative-apps'),
            'add_new_item'               => __('Add New App Tag', 'hoytcreative-apps'),
            'new_item_name'              => __('New App Tag Name', 'hoytcreative-apps'),
            'separate_items_with_commas' => __('Separate app tags with commas', 'hoytcreative-apps'),
            'add_or_remove_items'        => __('Add or remove app tags', 'hoytcreative-apps'),
            'choose_from_most_used'      => __('Choose from the most used app tags', 'hoytcreative-apps'),
            'not_found'                  => __('No app tags found.', 'hoytcreative-apps'),
            'menu_name'                  => __('Tags', 'hoytcreative-apps'),
        ];

        register_taxonomy(self::TAXONOMY_TAG, [self::POST_TYPE], [
            'hierarchical'          => false,
            'labels'                => $tag_labels,
            'show_ui'               => true,
            'show_in_rest'          => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => ['slug' => 'app-tag'],
        ]);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes()
    {
        add_meta_box(
            'hc_app_details',
            __('App Details', 'hoytcreative-apps'),
            [$this, 'app_details_meta_box'],
            self::POST_TYPE,
            'normal',
            'high'
        );

        add_meta_box(
            'hc_app_links',
            __('App Links', 'hoytcreative-apps'),
            [$this, 'app_links_meta_box'],
            self::POST_TYPE,
            'side',
            'default'
        );
    }

    /**
     * App details meta box callback
     */
    public function app_details_meta_box($post)
    {
        wp_nonce_field('hc_app_details_nonce', 'hc_app_details_nonce');

        $version = get_post_meta($post->ID, '_hc_app_version', true);
        $developer = get_post_meta($post->ID, '_hc_app_developer', true);
        $platform = get_post_meta($post->ID, '_hc_app_platform', true);
        $status = get_post_meta($post->ID, '_hc_app_status', true);
        $featured = get_post_meta($post->ID, '_hc_app_featured', true);

?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="hc_app_version"><?php _e('Version', 'hoytcreative-apps'); ?></label>
                </th>
                <td>
                    <input type="text" id="hc_app_version" name="hc_app_version" value="<?php echo esc_attr($version); ?>" class="regular-text" />
                    <p class="description"><?php _e('Current version of the app', 'hoytcreative-apps'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="hc_app_developer"><?php _e('Developer', 'hoytcreative-apps'); ?></label>
                </th>
                <td>
                    <input type="text" id="hc_app_developer" name="hc_app_developer" value="<?php echo esc_attr($developer); ?>" class="regular-text" />
                    <p class="description"><?php _e('App developer or company name', 'hoytcreative-apps'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="hc_app_platform"><?php _e('Platform', 'hoytcreative-apps'); ?></label>
                </th>
                <td>
                    <select id="hc_app_platform" name="hc_app_platform">
                        <option value=""><?php _e('Select Platform', 'hoytcreative-apps'); ?></option>
                        <option value="web" <?php selected($platform, 'web'); ?>><?php _e('Web', 'hoytcreative-apps'); ?></option>
                        <option value="ios" <?php selected($platform, 'ios'); ?>><?php _e('iOS', 'hoytcreative-apps'); ?></option>
                        <option value="android" <?php selected($platform, 'android'); ?>><?php _e('Android', 'hoytcreative-apps'); ?></option>
                        <option value="desktop" <?php selected($platform, 'desktop'); ?>><?php _e('Desktop', 'hoytcreative-apps'); ?></option>
                        <option value="cross-platform" <?php selected($platform, 'cross-platform'); ?>><?php _e('Cross-Platform', 'hoytcreative-apps'); ?></option>
                    </select>
                    <p class="description"><?php _e('Primary platform for this app', 'hoytcreative-apps'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="hc_app_status"><?php _e('Status', 'hoytcreative-apps'); ?></label>
                </th>
                <td>
                    <select id="hc_app_status" name="hc_app_status">
                        <option value="active" <?php selected($status, 'active'); ?>><?php _e('Active', 'hoytcreative-apps'); ?></option>
                        <option value="development" <?php selected($status, 'development'); ?>><?php _e('In Development', 'hoytcreative-apps'); ?></option>
                        <option value="deprecated" <?php selected($status, 'deprecated'); ?>><?php _e('Deprecated', 'hoytcreative-apps'); ?></option>
                        <option value="discontinued" <?php selected($status, 'discontinued'); ?>><?php _e('Discontinued', 'hoytcreative-apps'); ?></option>
                    </select>
                    <p class="description"><?php _e('Current status of the app', 'hoytcreative-apps'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="hc_app_featured"><?php _e('Featured App', 'hoytcreative-apps'); ?></label>
                </th>
                <td>
                    <input type="checkbox" id="hc_app_featured" name="hc_app_featured" value="1" <?php checked($featured, '1'); ?> />
                    <label for="hc_app_featured"><?php _e('Mark this app as featured', 'hoytcreative-apps'); ?></label>
                </td>
            </tr>
        </table>
    <?php
    }

    /**
     * App links meta box callback
     */
    public function app_links_meta_box($post)
    {
        wp_nonce_field('hc_app_links_nonce', 'hc_app_links_nonce');

        $demo_url = get_post_meta($post->ID, '_hc_app_demo_url', true);
        $download_url = get_post_meta($post->ID, '_hc_app_download_url', true);
        $github_url = get_post_meta($post->ID, '_hc_app_github_url', true);
        $docs_url = get_post_meta($post->ID, '_hc_app_docs_url', true);

    ?>
        <p>
            <label for="hc_app_demo_url"><strong><?php _e('Demo URL', 'hoytcreative-apps'); ?></strong></label><br>
            <input type="url" id="hc_app_demo_url" name="hc_app_demo_url" value="<?php echo esc_url($demo_url); ?>" class="widefat" />
        </p>
        <p>
            <label for="hc_app_download_url"><strong><?php _e('Download URL', 'hoytcreative-apps'); ?></strong></label><br>
            <input type="url" id="hc_app_download_url" name="hc_app_download_url" value="<?php echo esc_url($download_url); ?>" class="widefat" />
        </p>
        <p>
            <label for="hc_app_github_url"><strong><?php _e('GitHub URL', 'hoytcreative-apps'); ?></strong></label><br>
            <input type="url" id="hc_app_github_url" name="hc_app_github_url" value="<?php echo esc_url($github_url); ?>" class="widefat" />
        </p>
        <p>
            <label for="hc_app_docs_url"><strong><?php _e('Documentation URL', 'hoytcreative-apps'); ?></strong></label><br>
            <input type="url" id="hc_app_docs_url" name="hc_app_docs_url" value="<?php echo esc_url($docs_url); ?>" class="widefat" />
        </p>
<?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id)
    {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check if this is the correct post type
        if (get_post_type($post_id) !== self::POST_TYPE) {
            return;
        }

        // Verify nonces
        if (isset($_POST['hc_app_details_nonce']) && wp_verify_nonce($_POST['hc_app_details_nonce'], 'hc_app_details_nonce')) {
            $this->save_app_details($post_id);
        }

        if (isset($_POST['hc_app_links_nonce']) && wp_verify_nonce($_POST['hc_app_links_nonce'], 'hc_app_links_nonce')) {
            $this->save_app_links($post_id);
        }
    }

    /**
     * Save app details
     */
    private function save_app_details($post_id)
    {
        $fields = [
            'hc_app_version' => '_hc_app_version',
            'hc_app_developer' => '_hc_app_developer',
            'hc_app_platform' => '_hc_app_platform',
            'hc_app_status' => '_hc_app_status',
        ];

        foreach ($fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
            }
        }

        // Handle featured checkbox
        $featured = isset($_POST['hc_app_featured']) ? '1' : '0';
        update_post_meta($post_id, '_hc_app_featured', $featured);
    }

    /**
     * Save app links
     */
    private function save_app_links($post_id)
    {
        $url_fields = [
            'hc_app_demo_url' => '_hc_app_demo_url',
            'hc_app_download_url' => '_hc_app_download_url',
            'hc_app_github_url' => '_hc_app_github_url',
            'hc_app_docs_url' => '_hc_app_docs_url',
        ];

        foreach ($url_fields as $field => $meta_key) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $meta_key, esc_url_raw($_POST[$field]));
            }
        }
    }

    /**
     * Add custom admin columns
     */
    public function admin_columns($columns)
    {
        $new_columns = [];
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['app_version'] = __('Version', 'hoytcreative-apps');
        $new_columns['app_platform'] = __('Platform', 'hoytcreative-apps');
        $new_columns['app_status'] = __('Status', 'hoytcreative-apps');
        $new_columns['app_featured'] = __('Featured', 'hoytcreative-apps');
        $new_columns['taxonomy-' . self::TAXONOMY_CATEGORY] = $columns['taxonomy-' . self::TAXONOMY_CATEGORY];
        $new_columns['date'] = $columns['date'];

        return $new_columns;
    }

    /**
     * Display custom admin column content
     */
    public function admin_column_content($column, $post_id)
    {
        switch ($column) {
            case 'app_version':
                $version = get_post_meta($post_id, '_hc_app_version', true);
                echo $version ? esc_html($version) : '—';
                break;

            case 'app_platform':
                $platform = get_post_meta($post_id, '_hc_app_platform', true);
                if ($platform) {
                    $platforms = [
                        'web' => __('Web', 'hoytcreative-apps'),
                        'ios' => __('iOS', 'hoytcreative-apps'),
                        'android' => __('Android', 'hoytcreative-apps'),
                        'desktop' => __('Desktop', 'hoytcreative-apps'),
                        'cross-platform' => __('Cross-Platform', 'hoytcreative-apps'),
                    ];
                    echo isset($platforms[$platform]) ? esc_html($platforms[$platform]) : esc_html($platform);
                } else {
                    echo '—';
                }
                break;

            case 'app_status':
                $status = get_post_meta($post_id, '_hc_app_status', true);
                if ($status) {
                    $statuses = [
                        'active' => '<span style="color: green;">●</span> ' . __('Active', 'hoytcreative-apps'),
                        'development' => '<span style="color: orange;">●</span> ' . __('Development', 'hoytcreative-apps'),
                        'deprecated' => '<span style="color: red;">●</span> ' . __('Deprecated', 'hoytcreative-apps'),
                        'discontinued' => '<span style="color: #999;">●</span> ' . __('Discontinued', 'hoytcreative-apps'),
                    ];
                    echo isset($statuses[$status]) ? $statuses[$status] : esc_html($status);
                } else {
                    echo '<span style="color: green;">●</span> ' . __('Active', 'hoytcreative-apps');
                }
                break;

            case 'app_featured':
                $featured = get_post_meta($post_id, '_hc_app_featured', true);
                echo $featured === '1' ? '⭐' : '—';
                break;
        }
    }
}

// Initialize the post type
new HC_Apps_Post_Type();
