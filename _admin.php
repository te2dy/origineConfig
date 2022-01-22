<?php
if (!defined('DC_CONTEXT_ADMIN')) {
  return;
}

global $core;

$_menu['Plugins']->addItem(
  __('origineConfig'),
  'plugin.php?p=origineConfig',
  null,//urldecode(dcPage::getPF('chezteddy/icon.png')),
  preg_match('/plugin.php\?p=origineConfig(&.*)?$/', $_SERVER['REQUEST_URI']),
  $core->auth->check('admin', $core->blog->id)
);
