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
  /* Name */        'origineConfig',
  /* Description */ 'A plugin to customize Origine theme',
  /* Author */      'Teddy',
  /* Version */     '2.0.2-beta',
  [
    'permissions' => 'admin',
    'type'        => 'plugin',
  ]
);