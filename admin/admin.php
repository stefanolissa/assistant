<?php

defined('ABSPATH') || exit;

// TODO: Move on activate
wp_mkdir_p(WP_CONTENT_DIR . '/cache/ai-agent');

add_action('admin_menu', function () {

    add_menu_page(
            'Assistant', 'Assistant', 'administrator', 'assistant',
            function () {
                include __DIR__ . '/index.php';
            },
            'dashicons-smiley', 6
    );

    add_submenu_page(
            'assistant', 'Settings', 'Settings', 'administrator', 'assistant-settings',
            function () {
                include __DIR__ . '/settings.php';
            }
    );
});

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/agent.php';

add_action('wp_ajax_assistant_message', function () {

    $response = AssistantAgent::make()->chat(
            new \NeuronAI\Chat\Messages\UserMessage($_POST['message'])
    );


    $content = $response->getContent();
    $content = str_replace("\n", '<br>', $content);

    echo wp_json_encode(['reply' => $content]);
    die();
});

