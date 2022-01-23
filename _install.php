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

  // Activation
  $core->blog->settings->origineConfig->put('activation', false, 'boolean', 'Enable/disable the settings', false, true);

  // Design
  $core->blog->settings->origineConfig->put('color_scheme', 'system', 'string', 'Color scheme', false, true);

  // Content formatting
  $core->blog->settings->origineConfig->put('content_font_family', 'serif', 'string', 'Font family', false, true);
  $core->blog->settings->origineConfig->put('content_font_size', 12, 'integer', 'Font size', false, true);
  $core->blog->settings->origineConfig->put('content_text_align', 'left', 'string', 'Text align', false, true);
  $core->blog->settings->origineConfig->put('content_hyphens', false, 'boolean', 'Hyphenation', false, true);

  // Head
  $core->blog->settings->origineConfig->put('meta_generator', false, 'boolean', 'Generator', false, true);
  $core->blog->settings->origineConfig->put('meta_og', false, 'boolean', 'Open Graph Protocole', false, true);
  $core->blog->settings->origineConfig->put('meta_twitter', false, 'boolean', 'Twitter Cards', false, true);

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
