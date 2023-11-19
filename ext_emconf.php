<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Pagination Test',
    'description' => 'A test for pagination and routing with query parameters',
    'category' => 'plugin',
    'author' => 'Daniel Dorndorf',
    'author_email' => 'dorndorf@featdd.de',
    'state' => 'beta',
    'version' => '0.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'php' => '8.2.0-8.2.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
