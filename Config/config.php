<?php
/**
 * @copyright   2020 Idea2 Collective GmbH. All rights reserved.
 * @author      Idea2
 *
 * @see        https://www.idea2.ch
 * @see        https://developer.mautic.org/#services
 */
return [
    'name' => 'Mautic Trello',
    'description' => 'Add Mautic Contacts to Trello.',
    'version' => '0.1.0',
    'routes' => [
        'main' => [
            'plugin_helloworld_world' => [
                'path' => '/hello/{world}',
                'controller' => 'Idea2TrelloBundle:Default:world',
                'defaults' => [
                    'world' => 'earth',
                ],
                'requirements' => [
                    'world' => 'earth|mars',
                ],
            ],
            'plugin_create_cards' => [
                'path' => '/trello/card/add',
                'controller' => 'Idea2TrelloBundle:Card:add',
            ],
            'plugin_trello_card_add' => [
                'path' => '/api/v1/trello/card',
                'method' => 'POST',
                'controller' => 'Idea2TrelloBundle:ApiCard:add',
            ],
        ],
        'api' => [
            'plugin_api_trello_card_add' => [
                'path' => '/v1/trello/card',
                'method' => 'POST',
                'controller' => 'Idea2TrelloBundle:ApiCard:add',
            ],
        ],
    ],
    'services' => [
        // 'forms' => [
        //     'mautic.form.type.idea2trello.card' => [
        //         'class'     => 'MauticPlugin\Idea2TrelloBundle\Form\CardType',
        //     ], ],
        'events' => [
            'mautic.channel.button.subscriber.trello' => [
                'class' => \MauticPlugin\Idea2TrelloBundle\Event\ButtonSubscriber::class,
                'arguments' => [
                    'router',
                    'translator',
                ],
            ],
        ],
        'others' => [
            'mautic.idea2trello.trello_api_service' => [
                'class' => \MauticPlugin\Idea2TrelloBundle\Service\TrelloApiService::class,
            ],
            
        ],
        
    ],
];
