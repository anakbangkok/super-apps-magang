<?php

return [
    'imports' => [
        'heading' => 'slugged', // Atur penanganan heading untuk impor
        'csv' => [
            'delimiter' => ',', // Delimiter untuk file CSV
        ],
    ],
    'exports' => [
        'heading' => 'slugged', // Atur penanganan heading untuk ekspor
        'csv' => [
            'delimiter' => ',', // Delimiter untuk file CSV
        ],
    ],
    'database' => [
        'chunk_size' => 1000, // Ukuran chunk data untuk impor besar
    ],
];
