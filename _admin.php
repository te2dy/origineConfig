<?php
if (!defined('DC_CONTEXT_ADMIN')) {
  return;
}

global $core;

$_menu['Plugins']->addItem(
  __('Origine'),
  'plugin.php?p=origineConfig',
  urldecode(dcPage::getPF('origineConfig/img/icon.png')),
  preg_match('/plugin.php\?p=origineConfig(&.*)?$/', $_SERVER['REQUEST_URI']),
  $core->auth->check('admin', $core->blog->id)
);
