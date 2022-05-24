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
    'content_share_link_twitter',
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
  $origine_settings_default = [
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

    // Content
    'content_post_list_type'        => 'standard',
    'content_post_list_first_image' => false,
    'content_font_family'           => 'serif',
    'content_font_size'             => 100,
    'content_text_align'            => 'left',
    'content_hyphens'               => 'disabled',
    'content_post_author_name'      => 'disabled',
    'content_post_list_author_name' => false,
    'content_share_link_email'      => false,
    'content_share_link_facebook'   => false,
    'content_share_link_print'      => false,
    'content_share_link_whatsapp'   => false,
    'content_share_link_twitter'    => false,
    'content_post_list_comments'    => false,
    'content_comment_links'         => true,
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

  if (is_array($core->blog->settings->origineConfig->origine_settings) && !empty($core->blog->settings->origineConfig->origine_settings)) {
    $origine_settings = $core->blog->settings->origineConfig->origine_settings;

    /**
     * A list of outdated settings.
     *
     * @since origineConfig 1.0
     */
    $settings_to_unset = [
      'global_activation',
      'post_list_type',
      'sidebar_enabled',
      'logo_url',
      'logo_url_2x',
      'logo_type',
      'post_author_name',
      'post_list_comments',
      'comment_links',
      'post_email_author',
      'share_link_twitter',
      'social_links_diaspora',
      'social_links_discord',
      'social_links_facebook',
      'social_links_github',
      'social_links_mastodon',
      'social_links_signal',
      'social_links_tiktok',
      'social_links_twitter',
      'social_links_whatsapp',
      'header_logo_type',
      'footer_social_links_whatsapp',
    ];

    // Deletes outdated settings.
    if (!empty($settings_to_unset)) {
      foreach ($settings_to_unset as $setting_id) {
        if (array_key_exists($setting_id, $origine_settings)) {
          unset($origine_settings[$setting_id]);
        }
      }
    }

    $core->blog->settings->origineConfig->put('origine_settings', $origine_settings, 'array', 'All Origine settings', false, true);
  } else {
    $origine_settings = $origine_settings_default;
  }

  $core->blog->settings->origineConfig->put('origine_settings', $origine_settings, 'array', 'All Origine settings', false, true);

  /*foreach ($origine_settings_default as $id => $value_default) {
    $type = gettype($value_default);
    $core->blog->settings->origineConfig->put($id, $value_default, $type, 'All Origine settings', false, true);
  }*/

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
