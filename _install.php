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

  // Appearance
  $core->blog->settings->origineConfig->put('color_scheme', 'system', 'string', 'Color scheme', false, true);
  $core->blog->settings->origineConfig->put('content_link_color', 'red', 'string', 'Link color', false, true);
  $core->blog->settings->origineConfig->put('css_transition', false, 'boolean', 'Color transition on link hover', false, true);
  $core->blog->settings->origineConfig->put('tb_align', 'left', 'string', 'Header and footer alignment', false, true);

  $core->blog->settings->origineConfig->put('content_font_family', 'serif', 'string', 'Font family', false, true);
  $core->blog->settings->origineConfig->put('content_font_size', 12, 'integer', 'Font size', false, true);
  $core->blog->settings->origineConfig->put('content_text_align', 'left', 'string', 'Text align', false, true);
  $core->blog->settings->origineConfig->put('content_hyphens', false, 'boolean', 'Hyphenation', false, true);

  // Head
  $core->blog->settings->origineConfig->put('meta_generator', false, 'boolean', 'Generator', false, true);
  $core->blog->settings->origineConfig->put('meta_og', false, 'boolean', 'Open Graph Protocole', false, true);
  $core->blog->settings->origineConfig->put('meta_twitter', false, 'boolean', 'Twitter Cards', false, true);

  // Post settings
  $core->blog->settings->origineConfig->put('post_author_name', false, 'boolean', 'Author name on posts', false, true);
  $core->blog->settings->origineConfig->put('post_list_author_name', false, 'boolean', 'Author name on posts in the post list', false, true);
  $core->blog->settings->origineConfig->put('comment_links', true, 'boolean', 'Link to the comment feed and trackbacks', false, true);

  // All styles
  $core->blog->settings->origineConfig->put('origine_styles', '', 'string', 'Origine styles', false, true);

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
