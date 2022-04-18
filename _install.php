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

  // Old settings to delete from the database on the next version.
  $settings_to_drop = [
    'activation',
    'global_activation',
    'color_scheme',
    'link_color',
    'css_transition',
    'meta_generator',
    'header_footer_align',
    'post_list_type',
    'widgets_enabled',
    'footer_enabled',
    'logo_url',
    'logo_url_2x',
    'logo_type',
    'content_font_family',
    'content_font_size',
    'content_text_align',
    'content_hyphens',
    'content_share_link_twitter'
    'post_author_name',
    'post_list_author_name',
    'post_list_comments',
    'comment_links',
    'post_email_author',
    'footer_credits',
    'social_links_diaspora',
    'social_links_discord',
    'social_links_facebook',
    'social_links_github',
    'social_links_mastodon',
    'social_links_signal',
    'social_links_tiktok',
    'social_links_twitter',
    'social_links_whatsapp',
    'origine_styles',
    'sidebar_enabled',
  ];

  // Deletes old settings.
  foreach($settings_to_drop as $setting_id) {
    $core->blog->settings->origineConfig->dropEvery($setting_id, true);
  }

  // Default settings to define in the database.
  $origine_settings = [
    'activation' => false,

    // Global
    'global_color_scheme'   => 'system',
    'global_color_link'     => 'red',
    'global_css_transition' => false,
    'global_meta_generator' => false,

    // Header
    'header_align'       => 'left',
    'header_widgets_nav' => true,
    'header_logo_url'    => '',
    'header_logo_url_2x' => '',
    'header_logo_type'   => 'square',

    // Content
    'content_post_list_type'        => 'standard',
    'content_post_list_first_image' => false,
    'content_font_family'           => 'serif',
    'content_font_size'             => 100,
    'content_text_align'            => 'left',
    'content_hyphens'               => 'disabled',
    'content_post_author_name'      => 'disabled',
    'content_post_list_author_name' => 0,
    'content_share_link_email'      => false,
    'content_share_link_facebook'   => false,
    'content_share_link_print'      => false,
    'content_share_link_whatsapp'   => false,
    'content_share_link_twitter'    => false,
    'content_post_list_comments'    => 0,
    'content_comment_links'         => 1,
    'content_post_email_author'     => 'disabled',

    // Widgets
    'widgets_enabled' => true,

    // Footer
    'footer_enabled'               => true,
    'footer_align'                 => 'left',
    'footer_credits'               => true,
    'footer_social_links_diaspora' => '',
    'footer_social_links_discord'  => '',
    'footer_social_links_facebook' => '',
    'footer_social_links_github'   => '',
    'footer_social_links_mastodon' => '',
    'footer_social_links_signal'   => '',
    'footer_social_links_tiktok'   => '',
    'footer_social_links_twitter'  => '',
    'footer_social_links_whatsapp' => '',
  ];

  $core->blog->settings->origineConfig->put('origine_settings', $origine_settings, 'array', 'All Origine settings', false, true);

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
