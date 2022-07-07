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
   * @since origineConfig 1.2
   */
  public static function default_settings($theme = 'origine')
  {
    
    $default_settings = [];

    $default_settings['activation'] = [
      'title'       => __('Enable extension settings'),
      'description' => __('If you do not check this box, your settings will be ignored.'),
      'type'        => 'checkbox',
      'default'     => 0,
    ];

    // Global.
    $default_settings['global_color_scheme'] = [
      'title'       => __('Color scheme'),
      'description' => '',
      'type'        => 'select',
      'choices'     => [
        __('System (default)') => 'system',
        __('Light')            => 'light',
        __('Dark')             => 'dark',
      ],
      'default'     => 'system',
    ];

    if ($theme === 'origine') {
      $global_color_secondary_default = 'red';
      $global_color_secondary_choices = [
        __('Red (default)')           => 'red',
        __('Blue') => 'blue',
        __('Green')         => 'green',
        __('Orange')        => 'orange',
        __('Purple')        => 'purple',
      ];
    } else {
      $global_color_secondary_default = 'blue';
      $global_color_secondary_choices = [
        __('Red')            => 'red',
        __('Blue (default)') => 'blue',
        __('Green')          => 'green',
        __('Orange')         => 'orange',
        __('Purple')         => 'purple',
      ];
    }

    $default_settings['global_color_secondary'] = [
      'title'       => __('Secondary color'),
      'description' => __('Especially used for links.'),
      'type'        => 'select',
      'choices'     => $global_color_secondary_choices,
      'default'     => $global_color_secondary_default,
    ];

    $default_settings['global_css_transition'] = [
      'title'       => __('Add a color transition on link hover'),
      'description' => __('Accessibility: transitions are automatically disabled when the user has requested its system to minimize the amount of non-essential motion.'),
      'type'        => 'checkbox',
      'default'     => 0,
    ];

    if ($theme === 'origine') {
      $default_settings['global_separator'] = [
        'title'       => __('Global separator'),
        'description' => __(''),
        'type'        => 'text',
        'default'     => '/',
      ];
    }

    $default_settings['global_meta_generator'] = [
      'title'       => __('Add <code>generator</code> meta tag'),
      'description' => __("Allows you to add information to your pages without displaying it on your readers' screen."),
      'type'        => 'checkbox',
      'default'     => 0,
    ];

    return $default_settings;
  }

  /**
   * An array of default settings of the plugin.
   * 
   * @since origineConfig 1.0
   */
  public static function default_settings_v1()
  {
    $origine_settings = [
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
   * @since origineConfig 1.2
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
