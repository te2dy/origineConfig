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

$new_version = $core->plugins->moduleInfo('origineConfig', 'version');
$old_version = $core->getVersion('origineConfig');

if (version_compare($old_version, $new_version, '>=')) {
  return;
}

try {
  $core->blog->settings->addNamespace('origineConfig');

  // Activation
  $core->blog->settings->origineConfig->put('activation', false, 'boolean', 'Enable/disable the plugin settings', false, true);

  /**
   * Appearance
   */

  // Colors
  $core->blog->settings->origineConfig->put('color_scheme', 'system', 'string', 'Color scheme', false, true);
  $core->blog->settings->origineConfig->put('link_color', 'red', 'string', 'Link color', false, true);
  $core->blog->settings->origineConfig->put('css_transition', false, 'boolean', 'Color transition on link hover', false, true);

  // Layout
  $core->blog->settings->origineConfig->put('header_footer_align', 'left', 'string', 'Header & footer alignment', false, true);
  $core->blog->settings->origineConfig->put('post_list_type', 'standard', 'string', 'Post list appearance', false, true);
  $core->blog->settings->origineConfig->put('sidebar_enabled', true, 'boolean', 'Enable the sidebar', false, true);
  $core->blog->settings->origineConfig->put('footer_enabled', true, 'boolean', 'Enable the footer', false, true);

  // Logo
  $core->blog->settings->origineConfig->put('logo_url', '', 'string', 'URL of the logo', false, true);
  $core->blog->settings->origineConfig->put('logo_url_2x', '', 'string', 'URL of the logo (x2)', false, true);
  $core->blog->settings->origineConfig->put('logo_type', 'square', 'string', 'The type of logo', false, true);

  // Text Formatting
  $core->blog->settings->origineConfig->put('content_font_family', 'serif', 'string', 'Font family', false, true);
  $core->blog->settings->origineConfig->put('content_font_size', 100, 'integer', 'Font size', false, true);
  $core->blog->settings->origineConfig->put('content_text_align', 'left', 'string', 'Text align', false, true);
  $core->blog->settings->origineConfig->put('content_hyphens', '', 'string', 'Hyphenation', false, true);

  // Post Settings
  $core->blog->settings->origineConfig->put('post_author_name', 'disabled', 'string', 'Author name on posts', false, true);
  $core->blog->settings->origineConfig->put('post_list_author_name', false, 'boolean', 'Author name on posts in the post list', false, true);
  $core->blog->settings->origineConfig->put('post_list_comments', false, 'boolean', 'Link to comments in the post list', false, true);
  $core->blog->settings->origineConfig->put('comment_links', true, 'boolean', 'Link to the comment feed and trackbacks', false, true);
  $core->blog->settings->origineConfig->put('post_email_author', 'disabled', 'string', 'Option to email the author of a post', false, true);

  // Footer Settings
  $core->blog->settings->origineConfig->put('footer_credits', true, 'boolean', 'Dotclear and Origine credits', false, true);
  $core->blog->settings->origineConfig->put('social_links_diaspora', '', 'string', 'Link to Diaspora account', false, true);
  $core->blog->settings->origineConfig->put('social_links_discord', '', 'string', 'Link to Discord server', false, true);
  $core->blog->settings->origineConfig->put('social_links_facebook', '', 'string', 'Link to Facebook account', false, true);
  $core->blog->settings->origineConfig->put('social_links_github', '', 'string', 'Link to GitHub account', false, true);
  $core->blog->settings->origineConfig->put('social_links_mastodon', '', 'string', 'Link to Mastodon account', false, true);
  $core->blog->settings->origineConfig->put('social_links_signal', '', 'string', 'Link to a Signal number or group', false, true);
  $core->blog->settings->origineConfig->put('social_links_tiktok', '', 'string', 'Link to TikTok account', false, true);
  $core->blog->settings->origineConfig->put('social_links_twitter', '', 'string', 'Link to Twitter account', false, true);
  $core->blog->settings->origineConfig->put('social_links_whatsapp', '', 'string', 'Link to a WhatsApp number or group', false, true);

  // Advanced Settings
  $core->blog->settings->origineConfig->put('meta_generator', false, 'boolean', 'Meta generator', false, true);

  // All styles in one string
  $core->blog->settings->origineConfig->put('origine_styles', '', 'string', 'All custom styles in one string', false, true);

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
