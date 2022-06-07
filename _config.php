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
  public static function default_settings()
  {
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

      // Content
      'content_post_list_type'        => 'standard',
      'content_post_list_first_image' => false,
      'content_font_family'           => 'serif',
      'content_font_size'             => 100,
      'content_text_align'            => 'left',
      'content_hyphens'               => 'disabled',
      'content_post_date_time'        => 'date',
      'content_post_entry_date_time'  => 'date',
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

    return $origine_settings;
  }
}
