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
   * An array of default section where settings are put.
   * 
   * @since origineConfig 2.0
   */
  public static function setting_sections()
  {
    $sections = [
      'global' => [
        'name'         => __('Global'),
        'sub_sections' => [
          'fonts'    => __('Fonts'),
          'colors'   => __('Colors'),
          'advanced' => __('Advanced settings'),
        ],
      ],
      'header' => [
        'name'         => __('Header'),
        'sub_sections' => [
          'layout' => __('Layout'),
          'logo'   => __('Logo (beta)'),
        ],
      ],
      'content' => [
        'name'         => __('Content'),
        'sub_sections' => [
          'post-list'       => __('Post list'),
          'text-formatting' => __('Text formatting'),
          'author'          => __('Author'),
          'comments'        => __('Comments'),
          'other'           => __('Other'),
        ],
      ],
      'widgets' => [
        'name'         => __('Widgets'),
        'sub_sections' => [
          'no-title' => '',
        ],
      ],
      'footer' => [
        'name'         => __('Footer'),
        'sub_sections' => [
          'no-title'     => '',
          'social-links' => __('Social links'),
        ],
      ],
    ];

    return $sections;
  }

  /**
   * An array of default settings of the plugin.
   * 
   * @since origineConfig 2.0
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
    if ($theme === 'origine') {
      $content_font_family_default = 'serif';
      $content_font_family_choices = [
        __('Serif (default)') => 'serif',
        __('Sans serif')      => 'sans-serif',
        __('Monospace')       => 'monospace',
      ];
    } else {
      $content_font_family_default = 'sans-serif';
      $content_font_family_choices = [
        __('Sans serif (default)') => 'sans-serif',
        __('Serif')                => 'serif',
        __('Monospace')            => 'monospace',
      ];
    }

    $default_settings['global_font_family'] = [
      'title'       => __('Font family'),
      'description' => __('In any case, your theme will load system fonts of the device from which your site is viewed. This allows to reduce loading times and to have a graphic continuity with the system.'),
      'type'        => 'select',
      'choices'     => $content_font_family_choices,
      'default'     => $content_font_family_default,
      'section'     => ['global', 'fonts'],
    ];

    $default_settings['global_font_size'] = [
      'title'       => __('Font size'),
      'description' => __('It is recommended not to change this setting. A size of 100% means that the texts on your site will be the size defined in the browser settings of each of your visitors.'),
      'type'        => 'select_int',
      'choices'     => [
        __('80%')                => 80,
        __('90%')                => 90,
        __('100% (recommended)') => 100,
        __('110%')               => 110,
        __('120%')               => 120,
      ],
      'default'     => 100,
      'section'     => ['global', 'fonts'],
    ];

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
      'section'     => ['global', 'colors'],
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
      'section'     => ['global', 'colors'],
    ];

    $default_settings['global_css_transition'] = [
      'title'       => __('Add a color transition on link hover'),
      'description' => __('Accessibility: transitions are automatically disabled when the user has requested its system to minimize the amount of non-essential motion.'),
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['global', 'colors'],
    ];

    $default_settings['global_meta_generator'] = [
      'title'       => __('Add <code>generator</code> meta tag'),
      'description' => __("Allows you to add information to your pages without displaying it on your readers' screen."),
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['global', 'advanced'],
    ];

    // Header.
    if ($theme === 'origine') {
      $default_settings['header_align'] = [
        'title'       => __('Header alignment'),
        'description' => '',
        'type'        => 'select',
        'choices'     => [
          __('Left (default)') => 'left',
          __('Center')         => 'center',
        ],
        'default'     => 'left',
        'section'     => ['header', 'layout'],
      ];

      $default_settings['header_logo_url'] = [
        'title'       => __('The URL of your logo'),
        'description' => '',
        'type'        => 'text',
        'default'     => '',
        'section'     => ['header', 'logo'],
      ];

      $default_settings['header_logo_url_2x'] = [
        'title'       => __('The URL of your logo for screens with doubled pixel density'),
        'description' => __('To ensure a good display on screens with doubled pixel density (Retina), please provide an image that is twice the size the previous one.'),
        'type'        => 'text',
        'default'     => '',
        'section'     => ['header', 'logo'],
      ];

      $default_settings['content_post_list_type'] = [
        'title'       => __('Displaying of posts'),
        'description' => '',
        'type'        => 'select',
        'choices'     => [
          __('Standard (default)') => 'standard',
          __('On one line')        => 'short',
          __('Full post')          => 'full',
        ],
        'default'     => 'standard',
        'section'     => ['content', 'post-list'],
      ];

      $default_settings['content_post_list_first_image'] = [
        'title'       => __('Display the first image of the post (beta)'),
        'description' => '',
        'type'        => 'checkbox',
        'default'     => 0,
        'section'     => ['content', 'post-list'],
      ];
    }

    $default_settings['content_text_align'] = [
      'title'       => __('Text align'),
      'description' => '',
      'type'        => 'select',
      'choices'     => [
        __('Left (default)')                  => 'left',
        __('Justify')                         => 'justify',
        __('Justify except on small screens') => 'justify_not_mobile',
      ],
      'default'     => 'left',
      'section'     => ['content', 'text-formatting'],
    ];

    $default_settings['content_hyphens'] = [
      'title'       => __('Automatic hyphenation'),
      'description' => '',
      'type'        => 'select',
      'choices'     => [
        __('Disable (default)')              => 'disabled',
        __('Enable')                         => 'enabled',
        __('Enable except on small screens') => 'enabled_not_mobile',
      ],
      'default'     => 'disabled',
      'section'     => ['content', 'text-formatting'],
    ];

    $default_settings['content_post_author_name'] = [
      'title'       => __('Author name on posts'),
      'description' => '',
      'type'        => 'select',
      'choices'     => [
        __('Not displayed (default)')     => 'disabled',
        __('Next to the date')            => 'date',
        __('Below the post as signature') => 'signature',
      ],
      'default'     => 'disabled',
      'section'     => ['content', 'author'],
    ];

    if ($theme === 'origine') {
      $default_settings['content_post_list_author_name'] = [
        'title'       => __('Display the author name in the post list'),
        'description' => '',
        'type'        => 'checkbox',
        'default'     => 0,
        'section'     => ['content', 'author'],
      ];

      $default_settings['global_separator'] = [
        'title'       => __('Global separator'),
        'description' => __("Character(s) used to separate certain elements inside the theme (example: the date from the author's name when the latter is displayed). Default: \"/\"."),
        'type'        => 'text',
        'default'     => '/',
        'section'     => ['content', 'other'],
      ];
    }

    $default_settings['content_comment_links'] = [
      'title'       => __('Add a link to the comment feed and trackbacks below the comment section'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 1,
      'section'     => ['content', 'comments'],
    ];

    $default_settings['content_post_email_author'] = [
      'title'       => __('Allow visitors to send email to authors of posts and pages'),
      'description' => sprintf(__('If this option is enabled, the email address of authors will be made public. If you prefer not to reveal email addresses, try the <a href="%s">Signal</a> plugin.'), 'https://plugins.dotaddict.org/dc2/details/signal'),
      'type'        => 'select',
      'choices'     => [
        __('No (default)')                => 'disabled',
        __('Only when comments are open') => 'comments_open',
        __('Always')                      => 'always',
      ],
      'default'     => 'disabled',
      'section'     => ['content', 'comments'],
    ];

    // Widgets.
    $default_settings['widgets_nav_position'] = [
      'title'       => __('Navigation widgets positioning'),
      'description' => '',
      'type'        => 'select',
      'choices'     => [
        __('Between header and main content')           => 'header_content',
        __('Between main content and footer (default)') => 'content_footer',
        __('Disabled')                                  => 'disabled',
      ],
      'default'     => 'content_footer',
      'section'     => ['widgets', 'no-title'],
    ];

    $default_settings['widgets_extra_enabled'] = [
      'title'       => __('Enable the extra widget area'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 1,
      'section'     => ['widgets', 'no-title'],
    ];

    // Footer.
    $default_settings['footer_enabled'] = [
      'title'       => __('Enable the footer'),
      'description' => __('If your footer is empty or you want to remove everything at the bottom of your pages, uncheck this setting.'),
      'type'        => 'checkbox',
      'default'     => 1,
      'section'     => ['footer', 'no-title'],
    ];

    $default_settings['footer_credits'] = [
      'title'       => __('Add a link to support Dotclear and Origine'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 1,
      'section'     => ['footer', 'no-title'],
    ];

    $default_settings['footer_align'] = [
      'title'       => __('Footer alignment'),
      'description' => '',
      'type'        => 'select',
      'choices'     => [
        __('Left (default)') => 'left',
        __('Center')         => 'center',
      ],
      'default'     => 'left',
      'section'     => ['footer', 'no-title'],
    ];

    $default_settings['footer_social_links_discord'] = [
      'title'       => __('Link to a Discord server'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
    ];

    $default_settings['footer_social_links_facebook'] = [
      'title'       => __('Link to a Facebook profile or page'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
    ];

    $default_settings['footer_social_links_github'] = [
      'title'       => __('Link to a GitHub profile or page'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
    ];

    $default_settings['footer_social_links_mastodon'] = [
      'title'       => __('Link to a Mastodon profile'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
    ];

    $default_settings['footer_social_links_signal'] = [
      'title'       => __('A Signal number or a group link'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
    ];

    $default_settings['footer_social_links_tiktok'] = [
      'title'       => __('Link to a TikTok profile'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
    ];

    $default_settings['footer_social_links_twitter'] = [
      'title'       => __('A Twitter username'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
    ];

    $default_settings['footer_social_links_whatsapp'] = [
      'title'       => __('A WhatsApp number or a group link'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
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
      // Content

      /*
      // To enable in the future.
      'content_share_link_email'      => false,
      'content_share_link_facebook'   => false,
      'content_share_link_print'      => false,
      'content_share_link_whatsapp'   => false,
      'content_share_link_twitter'    => false,
      */
      
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
