<?php

declare(strict_types=1);

return [
    'global' => [
        'group.id' => 'broker',
        'metadata.broker.list' => 'kafka:9092',
        'enable.auto.commit' => 'false',
    ],
    'topic'  => [
        'auto.offset.reset' => 'beginning',
        'allow.auto.create.topics' => 'true',
    ],
];
