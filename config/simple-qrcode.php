<?php

return [
    'default' => 'png',
    
    'renderers' => [
        'png' => [
            'extension' => 'png',
            'quality' => 90,
            'size' => 500,
            'margin' => 10,
            'back_color' => ['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0],
            'fore_color' => ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0],
        ],
        'eps' => [
            'extension' => 'eps',
            'quality' => 90,
            'size' => 500,
            'margin' => 10,
            'back_color' => ['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0],
            'fore_color' => ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0],
        ],
        'svg' => [
            'extension' => 'svg',
            'quality' => 90,
            'size' => 500,
            'margin' => 10,
            'back_color' => ['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0],
            'fore_color' => ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0],
        ],
    ],

    'image_type' => 'png',
    'image_quality' => 90,
    'size' => 500,
    'margin' => 10,
    'back_color' => ['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0],
    'fore_color' => ['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0],
    'format' => 'png',
    'encoding' => 'UTF-8',
    'errorCorrection' => 'H',
];
