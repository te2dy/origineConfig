<?php
/**
 * origineConfig, a plugin to customize Origine, a Dotclear theme.
 *
 * @copyright Teddy
 * @copyright GPL-3.0
 *
 * @since origineConfig 1.0.3
 */

if (!defined('DC_CONTEXT_ADMIN')) {
  return;
}

class origineConfigSettings {
  /**
   * An array of default settings of the plugin.
   * 
   * @since origineConfig
   */
  public static function default_settings()
  {
    $origine_settings = [
      'activation' => false,

      // Global
      'global_color_scheme'   => 'system',
      'global_color_link'     => 'red',
      'global_css_transition' => false,
      'global_separator'      => "/",
      'global_meta_generator' => false,

      // Header
      'header_align'       => 'left',
      'header_logo_url'    => '',
      'header_logo_url_2x' => '',

      // Content
      'content_post_list_type'        => 'standard',
      'content_post_list_first_image' => false,
      'content_font_family'           => 'serif',
      'content_font_size'             => 100,
      'content_text_align'            => 'left',
      'content_hyphens'               => 'disabled',
      /*'content_post_date_time'        => 'date',
      'content_post_entry_date_time'  => 'date',*/
      'content_post_author_name'      => 'disabled',
      'content_post_list_author_name' => false,
      /*
      // To enable in the future.
      'content_share_link_email'      => false,
      'content_share_link_facebook'   => false,
      'content_share_link_print'      => false,
      'content_share_link_whatsapp'   => false,
      'content_share_link_twitter'    => false,
      */
      'content_post_list_comments'    => false,
      'content_comment_links'         => true,
      'content_post_email_author'     => 'disabled',

      // Widgets
      'widgets_nav_position' => 'content_footer',
      'widgets_extra'        => true,
      'widgets_enabled'      => true, // TO DELETE

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

    return $origine_settings;
  }

  /**
   * An array of settings to delete from the setting list.
   * 
   * @since origineConfig 1.0
   */
  public static function settings_to_unset()
  {
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
      'header_widgets_nav',
      'widgets_enabled',
    ];

    return $settings_to_unset;
  }
}
