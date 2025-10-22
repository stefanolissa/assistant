<?php

defined('ABSPATH') || exit;

use NeuronAI\Agent;
use NeuronAI\SystemPrompt;
use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Anthropic\Anthropic;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\Tool;
use NeuronAI\Tools\ToolProperty;
use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\History\FileChatHistory;

class AssistantAgent extends Agent {

    protected function provider(): AIProviderInterface {
        $settings = get_option('assistant', []);
        switch ($settings['provider']) {
            case 'mistral':
                return new NeuronAI\Providers\Mistral\Mistral(
                        key: $settings['mistral_key'],
                        model: $settings['mistral_model'] ?: 'mistral-medium-2508',
                );
            case 'openai':
                return new OpenAI(
                        key: $settings['openai_key'],
                        model: $settings['openai_model'] ?: 'gpt-5-nano',
                );
        }
    }

    public function instructions(): string {
        return (string) new SystemPrompt(
                        background:
                        [
                            "Assist the user invoking the provided tools. If a tool cannot be identify, stop.",
                            "Do not build an answer if a tool is not available, reply there is no tool for that request."
                        ],
                        steps:
                        [
                            "Find the correct tool from the provided ones to execute the task",
                            "Check if the user provided the correct parameters to use the tools, otherwise ask for more details",
                            "Execute the tool",
                            "Stop"
                        ],
                        output:
                        [
                            "Return the whole tool output without producing more content",
                        ]
                );
    }

    protected function tools(): array {

        if (!function_exists('wp_get_abilities')) {
            return [];
        }

        $abilities = wp_get_abilities();
        $tools = [];

        foreach ($abilities as $ability) {

            // TODO: Use the ability label?
            $tool = Tool::make(
                    str_replace('/', '-', $ability->get_name()),
                    $ability->get_description());

            $properties = $ability->get_input_schema()['properties'];
            foreach ($properties as $name => $data) {

                // TODO: Manage the required and the enums

                $tool->addProperty(new ToolProperty(
                                $name,
                                PropertyType::fromSchema($data['type']),
                                $data['description'],
                                $data['required'] ?? false,
                                $data['enum'] ?? []
                ));
            }

            //error_log(print_r($tool, true));
            // $args is an associative array that should ocntain exactly the input required by
            // the ability.
            // TODO: What about if I need to return an error? What's best for the LLM?

            $tool->setCallable(function (...$args) use ($ability) {

                $properties = $ability->get_input_schema()['properties'];

                $r = $ability->execute($args);

                if (is_array($r)) {
                    if (count($r) === 1) {
                        return array_shift($r);
                    }
                    $b = '';
                    foreach ($r as $k => $v) {
                        $b .= $k . ': ' . $v . "\n";
                    }

                    return $b;
                    //return wp_json_encode($r);
                }
                return $r;
            });

            $tools[] = $tool;
        }

        return $tools;
    }

//    protected function chatHistory(): ChatHistoryInterface {
//        return new FileChatHistory(
//                directory: WP_CONTENT_DIR . '/cache/ai-agent',
//                key: 'chat',
//                contextWindow: 2000
//        );
//    }
}
