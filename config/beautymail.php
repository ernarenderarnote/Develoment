<?php



return [



    // These CSS rules will be applied after the regular template CSS



    'css' => [
        '.button-content .button { background: #619d2b }',
        '
        html,
        body,
        #header,
        #background-table {
            background-color: #222222;
        }'
    ],



    'colors' => [



        'highlight' => '#004ca3',

        'button'    => '#004cad',



    ],



    'view' => [

        'senderName'  => null,

        'reminder'    => null,

        'unsubscribe' => null,

        'address'     => null,



        'logo'        => [

            'path'   => '%PUBLIC%/img/logo.png',

            'width'  => '',

            'height' => '',

        ],



        'twitter'  => null,

        'facebook' => null,

        'flickr'   => null,

    ],



];

