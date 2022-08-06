<?php
/**
 * origineConfig, a plugin to customize themes of the Origine family.
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
          'layout'   => __('Layout'),
          'colors'   => __('Colors and other styles'),
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
   * 
   * $default_settings = [
   *   'title'       => (string) The title of the setting,
   *   'description' => (string) The description of the setting,
   *   'type'        => (string) The type of the input (checkbox, string, select, select_int),
   *   'choices'     => [
   *     __('The name of the option') => 'the-id-of-the-option',
   *   ], only used with types "select" and "select_int"
   *   'default'     => (string) The default value of the setting,
   *   'section'     => (array) ['section', 'sub-section'] The section where to put the setting,
   *   'theme'       => (string|array) Theme(s) that support(s) this setting,
   * ];
   */
  public static function default_settings($theme = 'origine')
  {
    $theme = \dcCore::app()->blog->settings->system->theme;

    $default_settings = [];

    $default_settings['active'] = [
      'title'       => __('Enable extension settings'),
      'description' => __('If you do not check this box, your settings will be ignored.'),
      'type'        => 'checkbox',
      'default'     => 0,
      'theme'       => 'all',
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
      'theme'       => 'all',
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
      'theme'       => 'all',
    ];

    $default_settings['global_page_width'] = [
      'title'       => __('Default page width'),
      'description' => '',
      'type'        => 'select_int',
      'choices'     => [
        __('480px (default)') => 30,
        __('560px')           => 35,
        __('640px')           => 40,
      ],
      'default'     => 'system',
      'section'     => ['global', 'layout'],
      'theme'       => 'origine-mini',
    ];

    $default_settings['global_color_scheme'] = [
      'title'       => __('Color scheme'),
      'description' => __('When system is selected, the theme will use the color scheme of the user’s settings'),
      'type'        => 'select',
      'choices'     => [
        __('System (default)') => 'system',
        __('Light')            => 'light',
        __('Dark')             => 'dark',
      ],
      'default'     => 'system',
      'section'     => ['global', 'colors'],
      'theme'       => 'origine',
    ];

    if ($theme === 'origine') {
      $global_color_secondary_default = 'red';
      $global_color_secondary_choices = [
        __('Red (default)') => 'red',
        __('Blue')          => 'blue',
        __('Green')         => 'green',
        __('Orange')        => 'orange',
        __('Purple')        => 'purple',
      ];
    } else {
      $global_color_secondary_default = 'blue';
      $global_color_secondary_choices = [
        __('Blue (default)') => 'blue',
        __('Red')            => 'red',
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
      'theme'       => 'all',
    ];

    $default_settings['global_css_transition'] = [
      'title'       => __('Add a color transition on link hover'),
      'description' => __('Accessibility: transitions are automatically disabled when the user has requested its system to minimize the amount of non-essential motion.'),
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['global', 'colors'],
      'theme'       => 'origine-mini',
    ];

    $default_settings['global_css_border_radius'] = [
      'title'       => __('Round the corners'),
      'description' => __('Add a round angle to corner of borders.'),
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['global', 'colors'],
      'theme'       => 'all',
    ];

    $default_settings['global_meta_social'] = [
      'title'       => __('Add minimal social markups'),
      'description' => __('Useful to promote your content on social networks.'),
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['global', 'advanced'],
      'theme'       => 'all',
    ];

    $default_settings['global_meta_generator'] = [
      'title'       => __('Add <code>generator</code> meta tag'),
      'description' => __("Allows you to add information to your pages without displaying it on your readers' screen."),
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['global', 'advanced'],
      'theme'       => 'all',
    ];

    // Header.
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
      'theme'       => 'origine',
    ];

    $default_settings['header_logo_url'] = [
      'title'       => __('The URL of your logo'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['header', 'logo'],
      'theme'       => 'origine',
    ];

    $default_settings['header_logo_url_2x'] = [
      'title'       => __('The URL of your logo for screens with doubled pixel density'),
      'description' => __('To ensure a good display on screens with doubled pixel density (Retina), please provide an image that is twice the size the previous one.'),
      'type'        => 'text',
      'default'     => '',
      'section'     => ['header', 'logo'],
      'theme'       => 'origine',
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
      'theme'       => 'origine',
    ];

    $default_settings['content_post_list_first_image'] = [
      'title'       => __('Display the first image of the post (beta)'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['content', 'post-list'],
      'theme'       => 'origine',
    ];

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
      'theme'       => 'all',
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
      'theme'       => 'all',
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
      'theme'       => 'all',
    ];

    $default_settings['content_post_list_author_name'] = [
      'title'       => __('Display the author name in the post list'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['content', 'author'],
      'theme'       => 'origine',
    ];

    $default_settings['content_post_intro'] = [
      'title'       => __('Display post except as an introduction at the beginning of the posts'),
      'description' => __('Only if an except has been set.'),
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['content', 'other'],
      'theme'       => 'origine-mini',
    ];

    $default_settings['global_separator'] = [
      'title'       => __('Global separator'),
      'description' => __("Character(s) used to separate certain elements inside the theme (example: the date from the author's name when the latter is displayed). Default: \"/\"."),
      'type'        => 'text',
      'default'     => '/',
      'section'     => ['content', 'other'],
      'theme'       => 'origine',
    ];

    $default_settings['content_post_list_comment_link'] = [
      'title'       => __('Display the number of comments in the post list if the post has comments.'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['content', 'comments'],
      'theme'       => 'origine-mini',
    ];

    $default_settings['content_comment_links'] = [
      'title'       => __('Add a link to the comment feed and trackbacks below the comment section'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 1,
      'section'     => ['content', 'comments'],
      'theme'       => 'all',
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
      'theme'       => 'all',
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
      'theme'       => 'all',
    ];

    $default_settings['widgets_search_form'] = [
      'title'       => __('Add a search form at the top of the navigation widget area'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 0,
      'section'     => ['widgets', 'no-title'],
      'theme'       => 'origine-mini',
    ];

    $default_settings['widgets_extra_enabled'] = [
      'title'       => __('Enable the extra widget area'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 1,
      'section'     => ['widgets', 'no-title'],
      'theme'       => 'all',
    ];

    // Footer.
    $default_settings['footer_enabled'] = [
      'title'       => __('Enable the footer'),
      'description' => __('If your footer is empty or you want to remove everything at the bottom of your pages, uncheck this setting.'),
      'type'        => 'checkbox',
      'default'     => 1,
      'section'     => ['footer', 'no-title'],
      'theme'       => 'all',
    ];

    $default_settings['footer_credits'] = [
      'title'       => __('Add a link to support Dotclear and Origine'),
      'description' => '',
      'type'        => 'checkbox',
      'default'     => 1,
      'section'     => ['footer', 'no-title'],
      'theme'       => 'all',
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
      'theme'       => 'all',
    ];

    $default_settings['footer_social_links_discord'] = [
      'title'       => __('Link to a Discord server'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
      'theme'       => 'all',
    ];

    $default_settings['footer_social_links_facebook'] = [
      'title'       => __('Link to a Facebook profile or page'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
      'theme'       => 'all',
    ];

    $default_settings['footer_social_links_github'] = [
      'title'       => __('Link to a GitHub profile or page'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
      'theme'       => 'all',
    ];

    $default_settings['footer_social_links_mastodon'] = [
      'title'       => __('Link to a Mastodon profile'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
      'theme'       => 'all',
    ];

    $default_settings['footer_social_links_signal'] = [
      'title'       => __('A Signal number or a group link'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
      'theme'       => 'all',
    ];

    $default_settings['footer_social_links_tiktok'] = [
      'title'       => __('Link to a TikTok profile'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
      'theme'       => 'all',
    ];

    $default_settings['footer_social_links_twitter'] = [
      'title'       => __('A Twitter username'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
      'theme'       => 'all',
    ];

    $default_settings['footer_social_links_whatsapp'] = [
      'title'       => __('A WhatsApp number or a group link'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => ['footer', 'social-links'],
      'theme'       => 'all',
    ];

    $default_settings['global_css'] = [
      'title'       => __('All theme styles'),
      'description' => '',
      'type'        => 'text',
      'default'     => '',
      'section'     => [],
      'theme'       => 'all',
    ];

    return $default_settings;
  }

  /**
   * An array of settings to delete from the setting list.
   * 
   * @return array An array if setting ID to remove from the database.
   * 
   * @since origineConfig 1.2
   */
  public static function settings_to_unset()
  {
    $settings_to_unset = [
      'activation',
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