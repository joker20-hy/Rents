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
    'VERIFICATION' => [
        'CODE_LENGTH' => 7,
        'TOKEN_LENGTH' => 64,
        'EXPIRE' => 10
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
        'PERIODIC' => 2,
        'BY_RENTERS' => 3
    ],
    'SERVICE_TYPE_NAME' => [
        'PER_UNIT' => 'Theo đơn vị',
        'PERIODIC' => 'Định kỳ',
        'BY_RENTERS' => 'Theo người thuê trọ'
    ],
    'OWNER_ROLE' => [
        'OWNER' => 1,
        'TENANT' => 2
    ],
    'REVIEW' => [
        'TYPE' => [
            'OWNER' => 1,
            'RENTER' => 2,
            'HOUSE' => 3,
            'ROOM' => 4
        ]
    ],
    'ROOM_FILTER' => [
        'PRICE' => [
            [
                'title' => 'Dưới 1 triệu',
                'min' => 0,
                'max' => 1000000
            ],
            [
                'title' => 'Từ 1 đến 1,5 triệu',
                'min' => 1000000,
                'max' => 1500000
            ],
            [
                'title' => 'Từ 1,5 đến 2 triệu',
                'min' => 1500000,
                'max' => 2000000
            ],
            [
                'title' => 'Từ 2 đến 4 triệu',
                'min' => 2000000,
                'max' => 4000000
            ],
            [
                'title' => 'Trên 4 triệu',
                'min' => 4000000,
                'max' => null
            ]
        ],
        'ACREAGE' => [
            [
                'title' => 'Dưới 14 m2',
                'min' => 0,
                'max' => 14
            ],
            [
                'title' => 'Từ 14 - 18 m2',
                'min' => 14,
                'max' => 18
            ],
            [
                'title' => 'Từ 18 - 24m2',
                'min' => 18,
                'max' => 24
            ],
            [
                'title' => 'Trên 24m2',
                'min' => 24,
                'max' => null
            ]
        ]
    ],
    'ROOM_SORT' => [
        [
            'title' => 'Đánh giá cao nhất',
            'feild' => 'avg_rate',
            'order' => 'desc'
        ],
        [
            'title' => 'Giá tăng dần',
            'feild' => 'price',
            'order' => 'asc'
        ],
        [
            'title' => 'Giá giảm dần',
            'feild' => 'price',
            'order' => 'desc'
        ]
    ],
    'ROOM_STATUS' => [
        'waiting' => 0,
        'rented' => 1,
        'half' => 2
    ],
    'MAX_SIZE_IMAGE' => 2,
    'SEARCH' => [
        'TYPE' => [
            'FREE' => 1,
            'ROOMMATE' => 2
        ]
    ]
];
