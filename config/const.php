<?php

return [
    'USER' => [
        'ROLE' => [
            'NORMAL' => 0,
            'ADMIN' => 1,
            'OWNER' => 2,
            'RENTER' => 3
        ],
        'ROLE_NAME' => [
            0 => 'Normal user',
            1 => 'Admin',
            2 => 'House owner'
        ],
        'UNVERIFIED' => 0,
        'VERIFIED' => 1
    ],
    'PLACE_TYPE' => [
        'PROVINCE' => 1,
        'DISTRICT' => 2,
        'AREA' => 3
    ],
    'FOLDER' => [
        'PROFILE' => 'images',
        'HOUSE' => 'houses',
        'ROOM' => 'rooms'
    ],
    'FOLDER_TYPE' => [
        'PROFILE' => 1,
        'HOUSE' => 2
    ],
    'SERVICE_TYPE' => [
        'PER_UNIT' => 1,
        'PERIODIC' => 2
    ],
    'OWNER_ROLE' => [
        'OWNER' => 1,
        'TENANT' => 2
    ],
    'REVIEW' => [
        'RECEIVER_TYPE' => [
            'RENTER' => 1,
            'HOUSE' => 2,
            'ROOM' => 3
        ]
    ]
];
