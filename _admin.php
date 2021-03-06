<?php
/**
 * origineConfig, a plugin to customize Origine, a Dotclear theme.
 *
 * @copyright Teddy
 * @copyright GPL-3.0
 */

if (!defined('DC_CONTEXT_ADMIN')) {
  return;
}

global $core;

$_menu['Plugins']->addItem(
  __('Origine Settings'),
  'plugin.php?p=origineConfig',
  urldecode(dcPage::getPF('origineConfig/img/icon.png')),
  preg_match('/plugin.php\?p=origineConfig(&.*)?$/', $_SERVER['REQUEST_URI']),
  $core->auth->check('admin', $core->blog->id)
);
