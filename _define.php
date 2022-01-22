<?php
if (!defined('DC_RC_PATH')) {
  return;
}

$this->registerModule(
  /* Name */         'origineConfig',
  /* Description*/   'A plugin to customize Origine theme',
  /* Author */       'Teddy',
  /* Version */      'alpha',
  array(
    'permissions' => 'admin',
    'requires'    => [['core']],
    'type'        => 'plugin',
  )
);
