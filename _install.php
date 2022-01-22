<?php
if (!defined('DC_CONTEXT_ADMIN')) {
  return;
}

$new_version = $core->plugins->moduleInfo('origineConfig', 'version');
$old_version = $core->getVersion('origineConfig');

if (version_compare($old_version, $new_version, '>=')) {
  return;
}

try {
  $core->blog->settings->addNamespace('origineConfig');

  $core->blog->settings->origineConfig->put('activation', false, 'boolean', 'Enable/disable the settings', false, true);
  $core->blog->settings->origineConfig->put('content_font_family', 'serif', 'string', 'Font family', false, true);
  $core->blog->settings->origineConfig->put('content_font_size', 12, 'integer', 'Font size', false, true);
  $core->blog->settings->origineConfig->put('content_text_align', 'left', 'string', 'Text align', false, true);
  $core->blog->settings->origineConfig->put('content_hyphens', false, 'boolean', 'Hyphenation', false, true);

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
