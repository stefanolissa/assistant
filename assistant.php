<?php

/**
 * Plugin Name: Assistant
 * Description: Assistant based on AI to interact with your WP abilities
 * Version: 0.0.4
 * Author: satollo
 * Author URI: https://www.satollo.net
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: assistant
 * Requires at least: 6.9
 * Requires PHP: 8.0
 * Plugin URI: https://www.satollo.net/plugins/assistant
 * Update URI: satollo_assistant
 */
defined('ABSPATH') || exit;

define('ASSISTANT_VERSION', '0.0.4');

//register_activation_hook(__FILE__, function () {
//    require_once __DIR__ . '/admin/activate.php';
//});
//register_deactivation_hook(__FILE__, function () {
//});

if (is_admin()) {
    require_once __DIR__ . '/admin/admin.php';
    // Test abilities
    //require_once __DIR__ . '/includes/abilities.php';
}

/**
 * Update
 */
add_filter('update_plugins_satollo_assistant', function ($update, $plugin_data, $plugin_file, $locales) {

    $data = get_option('assistant_update_data');
    if ($data->updated < time() - WEEK_IN_SECONDS || isset($_GET['force-check'])) {
        $data = null;
    }

    if (!$data) {
        $response = wp_remote_get('https://www.satollo.net/repo/assistant/assistant.json');
        $data = json_decode(wp_remote_retrieve_body($response));
        if (is_object($data)) {
            $data->updated = time();
            update_option('assistant_update_data', $data);
        }
    }

    if (isset($data->version)) {

        $update = [
            'version' => $data->version,
            'slug' => 'assistant',
            'url' => 'https://www.satollo.net/plugins/assistant',
            'package' => 'https://www.satollo.net/repo/assistant/assistant.zip'
        ];
        return $update;
    } else {
        return false;
    }
}, 0, 4);

