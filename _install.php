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

include __DIR__ . '/settings.php';

$new_version = $core->plugins->moduleInfo('origineConfig', 'version');
$old_version = $core->getVersion('origineConfig');

if (version_compare($old_version, $new_version, '>=')) {
  return;
}

try {
  $core->blog->settings->addNamespace('origineConfig');

  /**
   * Old settings to delete from the database on the next version.
   *
   * @since origineConfig 1.0
   */
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
  $origine_settings_default = origineConfigSettings::default_settings();

  if (is_array($core->blog->settings->origineConfig->origine_settings) && !empty($core->blog->settings->origineConfig->origine_settings)) {
    $origine_settings = $core->blog->settings->origineConfig->origine_settings;

    /**
     * A list of outdated settings to remove.
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
      'content_post_list_comments',
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

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
