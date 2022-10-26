<?php
/**
 * origineConfig, a plugin to customize Origine, a Dotclear theme.
 *
 * @copyright Teddy
 * @copyright GPL-3.0
 */

if (!defined('DC_RC_PATH')) {
    return;
}

$this->registerModule(
    'origineConfig',                       // Name
    'A plugin to customize Origine theme', // Description
    'Teddy',                               // Author
    '2.1.0.2',                             // Version
    [
        'permissions' => 'admin',
        'requires'    => [['core', '2.24']],
        'type'        => 'plugin'
    ]
);
