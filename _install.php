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
    'color_scheme',
    'color_link',
    'css_transition',
    'meta_generator',
    'header_footer_align',
    'post_list_type',
    'sidebar_enabled',
    'footer_enabled',
    'logo_url',
    'logo_url_2x',
    'logo_type',
    'content_font_family',
    'content_font_size',
    'content_text_align',
    'content_hyphens',
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
  ];

  // Deletes old settings.
  foreach($settings_to_drop as $setting_id) {
    if ($core->blog->settings->origineConfig->$setting_id) {
      $core->blog->settings->origineConfig->dropEvery($setting_id, true);
    }
  }

  // Default settings to define in the database.
  $origine_settings = [
    'activation'            => false,
    'global_color_scheme'   => 'system',
    'global_color_link'     => 'red',
    'global_css_transition' => false,
    'global_meta_generator' => false,

    'header_align'          => 'left',
    'header_widgets_nav'    => true,

    'footer_align'          => 'left',
    'post_list_type'        => 'standard',
    'sidebar_enabled'       => true,
    'footer_enabled'        => true,
    'logo_url'              => '',
    'logo_url_2x'           => '',
    'logo_type'             => 'square',
    'content_font_family'   => 'serif',
    'content_font_size'     => 100,
    'content_text_align'    => 'left',
    'content_hyphens'       => 'disabled',
    'post_author_name'      => 'disabled',
    'post_list_author_name' => 0,
    'post_list_comments'    => 0,
    'comment_links'         => 1,
    'post_email_author'     => 'disabled',
    'share_link_twitter'    => false,
    'footer_credits'        => true,
    'social_links_diaspora' => '',
    'social_links_discord'  => '',
    'social_links_facebook' => '',
    'social_links_github'   => '',
    'social_links_mastodon' => '',
    'social_links_signal'   => '',
    'social_links_tiktok'   => '',
    'social_links_twitter'  => '',
    'social_links_whatsapp' => '',
  ];

  $core->blog->settings->origineConfig->put('origine_settings', $origine_settings, 'array', 'All Origine settings', false, true);

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
