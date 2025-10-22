<?php
defined('ABSPATH') || exit;

// Dummy abilities
add_action('abilities_api_init', function () {

//    $r = wp_register_ability('test/site-health',
//            [
//                'label' => 'Checks the site health',
//                'description' => 'Checks the site health and returns a report with the main issues about the system, plugins, scheduler, optimizations',
//                'input_schema' => [
//                    'type' => 'object',
//                    'properties' => [],
//                    'additionalProperties' => false,
//                ],
//                'output_schema' => [
//                    'type' => 'object',
//                    'properties' => [
//                        'result' => [
//                            'type' => 'string',
//                            'description' => 'Some kind of data',
//                            'minLength' => 0
//                        ],
//                    ],
//                ],
//                'execute_callback' => function () {
//                    return ['result' => 'The site is ok, there is nothing to do.'];
//                },
//                'permission_callback' => function () {
//                    return true;
//                },
//                'meta' => [
//                    'category' => 'test',
//                ],
//            ]
//    );

//    $r = wp_register_ability('test/site-health',
//            [
//                'label' => 'Checks the site health',
//                'description' => 'Checks the site health and returns a report with the main issues about the system, plugins, scheduler, optimizations',
//                'input_schema' => [
//                    'type' => 'object',
//                    'properties' => [
//                        'number_of_posts' => [
//                            'type' => 'integer',
//                            'description' => 'Number of posts to include into the newsletter',
//                            'minLength' => 0
//                        ],
//                    ],
//                    'additionalProperties' => false,
//                ],
//                'output_schema' => [
//                    'type' => 'object',
//                    'properties' => [
//                        'field1' => [
//                            'type' => 'string',
//                            'description' => 'Some kind of data',
//                            'minLength' => 0
//                        ],
//                    ],
//                ],
//                'execute_callback' => function () {
//                    return 'The newsletter has been created, no other actions are needed. The newsletter can be modified or sent from the editing page '
//        . ' at url https://localhost/edit-newsletter';
//                },
//                'permission_callback' => function () {
//                    return true;
//                },
//                'meta' => [
//                    'category' => 'dummy',
//                ],
//            ]
//    );
//
//    $r = wp_register_ability('newsletter/get-subscribers-statistics',
//            [
//                'label' => 'Generates statistics about the subscribers',
//                'description' => 'Generates statistics about the subscribers returning the number of confirmed, unconfirmed, bounced.',
//                'input_schema' => [
//                    'type' => 'object',
//                    'properties' => [],
//                    'additionalProperties' => false,
//                ],
//                'output_schema' => [
//                    'type' => 'object',
//                    'properties' => [
//                        'confirmed' => [
//                            'type' => 'integer',
//                            'description' => 'Number of confirmed subscribers',
//                        ],
//                        'not_confirmed' => [
//                            'type' => 'integer',
//                            'description' => 'Number of not confirmed subscribers',
//                        ],
//                        'bounced' => [
//                            'type' => 'integer',
//                            'description' => 'Number of bounced subscribers',
//                        ],
//                    ],
//                ],
//                'execute_callback' => function ($input) {
//                    error_log(print_r($input, true));
//                    return ['confirmed' => 12, 'not_confirmed' => 3, 'bounced' => 4];
//                },
//                'permission_callback' => function () {
//                    return true;
//                },
//                'meta' => [
//                    'category' => 'dummy',
//                ],
//            ]
//    );
//
//                $r = wp_register_ability('newsletter/update-subscriber',
//            [
//                'label' => 'Update a subscriber',
//                'description' => 'Update a subscriber chaging one or more details between status (confirmed, unconfirmed), the first name and the last name',
//                'input_schema' => [
//                    'type' => 'object',
//                    'properties' => [
//                        'email' => [
//                            'type' => 'string',
//                            'description' => 'The subscriber email to identify and update it',
//                            'required' => true,
//                        ],
//                        'status' => [
//                            'type' => 'string',
//                            'description' => 'The subscriber status with value confirmed or unconfirmed',
//                            'required' => false,
//                            'enum' => ['confirmed', 'unconfirmed']
//                        ]
//                    ],
//                    'additionalProperties' => false,
//                ],
//                'output_schema' => [
//                    'type' => 'object',
//                    'properties' => [
//                        'result' => [
//                            'type' => 'string',
//                            'description' => 'If the operation has been successful or not',
//                        ]
//                    ],
//                ],
//                'execute_callback' => function () {
//                    return ['result' => 'Subscriber not found, provide another email address.'];
//
//                },
//                'permission_callback' => function () {
//                    return true;
//                },
//                'meta' => [
//                    'category' => 'dummy',
//                ],
//            ]
//    );
});

