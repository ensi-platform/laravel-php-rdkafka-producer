<?php

// configurattion options can be found here: https://github.com/edenhill/librdkafka/blob/master/CONFIGURATION.md
// if an option is set to null it is ignored.
return [
    'producers' => [
        'default' => [
            'metadata.broker.list' => '127.0.0.1',
            'security.protocol' => 'plaintext',
            'log_level' => LOG_DEBUG,

            // producer specific options
            'compression.codec' =>'snappy',
        ],
    ],
];
