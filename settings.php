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
            'global'   => [
                'name'         => __('settings-section-global-name'),
                'sub_sections' => [
            'fonts'    => __('settings-section-global-fonts-title'),
            'layout'   => __('settings-section-global-layout-title'),
            'colors'   => __('settings-section-global-colors-title'),
            'advanced' => __('settings-section-global-advance-title'),
        ],
            ],
            'header' => [
                'name'         => __('settings-section-header-name'),
                'sub_sections' => [
                    'layout' => __('settings-section-header-layout-title'),
                    'logo'   => __('settings-section-header-logo-title'),
                ],
            ],
            'content' => [
                'name'         => __('settings-section-content-name'),
                'sub_sections' => [
                    'post-list'       => __('settings-section-content-postlist-title'),
                    'text-formatting' => __('settings-section-content-textformatting-title'),
                    'images'          => __('settings-section-content-images-title'),
                    'author'          => __('settings-section-content-author-title'),
                    'comments'        => __('settings-section-content-reactions-title'),
                    'other'           => __('settings-section-content-other-title'),
                ],
            ],
            'widgets' => [
                'name'         => __('settings-section-widgets-name'),
                'sub_sections' => [
                    'no-title' => '',
                ],
            ],
            'footer' => [
                'name'         => __('settings-section-footer-name'),
                'sub_sections' => [
                    'no-title'     => '',
                    'social-links' => __('settings-section-footer-sociallinks-title'),
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
     *     'title'       => (string) The title of the setting,
     *     'description' => (string) The description of the setting,
     *     'type'        => (string) The type of the input (checkbox, string, select, select_int),
     *     'choices'     => [
     *         __('The name of the option') => 'the-id-of-the-option',
     *     ], only used with types "select" and "select_int"
     *     'default'     => (string) The default value of the setting,
     *     'section'     => (array) ['section', 'sub-section'] The section where to put the setting,
     *     'theme'       => (string|array) Theme(s) that support(s) this setting,
     * ];
     */
    public static function default_settings($theme = 'origine')
    {
        $theme = \dcCore::app()->blog->settings->system->theme;

        $default_settings = [];

        $default_settings['active'] = [
            'title'       => __('settings-option-activation-title'),
            'description' => __('settings-option-activation-description'),
            'type'        => 'checkbox',
            'default'     => 0,
            'theme'       => ['origine', 'origine-mini']
        ];

        // Global.
        if ($theme === 'origine') {
            $content_font_family_default = 'serif';
            $content_font_family_choices = [
                __('settings-option-global-fontfamily-serif-default') => 'serif',
                __('settings-option-global-fontfamily-sansserif')     => 'sans-serif',
                __('settings-option-global-fontfamily-mono')    => 'monospace'
            ];
        } else {
            $content_font_family_default = 'sans-serif';
            $content_font_family_choices = [
                __('settings-option-global-fontfamily-sansserif-default') => 'sans-serif',
                __('settings-option-global-fontfamily-serif')             => 'serif',
                __('settings-option-global-fontfamily-mono')              => 'monospace'
            ];
        }

        $default_settings['global_font_family'] = [
            'title'       => __('settings-option-global-fontfamily-title'),
            'description' => __('settings-option-global-fontfamily-description'),
            'type'        => 'select',
            'choices'     => $content_font_family_choices,
            'default'     => $content_font_family_default,
            'section'     => ['global', 'fonts'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['global_font_size'] = [
            'title'       => __('settings-option-global-fontsize-title'),
            'description' => __('settings-option-global-fontsize-description'),
            'type'                => 'select_int',
            'choices'     => [
                __('settings-option-global-fontsize-80')          => 80,
                __('settings-option-global-fontsize-90')          => 90,
                __('settings-option-global-fontsize-100-default') => 100,
                __('settings-option-global-fontsize-110')         => 110,
                __('settings-option-global-fontsize-120')         => 120
            ],
            'default'     => 100,
            'section'     => ['global', 'fonts'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['global_page_width'] = [
            'title'       => __('settings-option-global-pagewidth-title'),
            'description' => __('settings-option-global-pagewidth-description'),
            'type'        => 'select_int',
            'choices'     => [
                __('settings-option-global-pagewidth-title-30-default') => 30,
                __('settings-option-global-pagewidth-title-35')         => 35,
                __('settings-option-global-pagewidth-title-40')         => 40
            ],
            'default'     => 'system',
            'section'     => ['global', 'layout'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['global_color_scheme'] = [
            'title'       => __('settings-option-global-colorscheme-title'),
            'description' => __('settings-option-global-colorscheme-description'),
            'type'        => 'select',
            'choices'     => [
                __('settings-option-global-colorscheme-system-default') => 'system',
                __('settings-option-global-colorscheme-light')          => 'light',
                __('settings-option-global-colorscheme-dark')           => 'dark'
            ],
            'default'     => 'system',
            'section'     => ['global', 'colors'],
            'theme'       => ['origine']
        ];

        if ($theme === 'origine') {
            $global_color_secondary_default = 'red';
            $global_color_secondary_choices = [
                __('settings-option-global-secondarycolor-red-default') => 'red',
                __('settings-option-global-secondarycolor-blue')        => 'blue',
                __('settings-option-global-secondarycolor-green')       => 'green',
                __('settings-option-global-secondarycolor-orange')      => 'orange',
                __('settings-option-global-secondarycolor-purple')      => 'purple'
            ];
        } else {
            $global_color_secondary_default = 'blue';
            $global_color_secondary_choices = [
                __('settings-option-global-secondarycolor-blue-default') => 'blue',
                __('settings-option-global-secondarycolor-gray')         => 'gray',
                __('settings-option-global-secondarycolor-red')          => 'red',
                //__('settings-option-global-secondarycolor-purple')       => 'purple',
                //__('settings-option-global-secondarycolor-green')        => 'green'
            ];
        }

        ksort($global_color_secondary_choices);

        $default_settings['global_color_secondary'] = [
            'title'       => __('settings-option-global-secondarycolor-title'),
            'description' => __('settings-option-global-secondarycolor-description'),
            'type'        => 'select',
            'choices'     => $global_color_secondary_choices,
            'default'     => $global_color_secondary_default,
            'section'     => ['global', 'colors'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['global_css_transition'] = [
            'title'       => __('settings-option-global-colortransition-title'),
            'description' => __('settings-option-global-colortransition-description'),
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['global', 'colors'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['global_css_border_radius'] = [
            'title'       => __('settings-option-global-roundcorner-title'),
            'description' => __('settings-option-global-roundcorner-description'),
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['global', 'colors'],
            'theme'       => ['origine-mini']
        ];

        $default_settings['global_meta_social'] = [
            'title'       => __('settings-option-global-minimalsocialmarkups-title'),
            'description' => __('settings-option-global-minimalsocialmarkups-description'),
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['global', 'advanced'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['global_meta_generator'] = [
            'title'       => __('settings-option-global-metagenerator-title'),
            'description' => __('settings-option-global-metagenerator-description'),
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['global', 'advanced'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['global_meta_pingback'] = [
            'title'       => __('settings-option-global-metapingback-title'),
            'description' => '',
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['global', 'advanced'],
            'theme'       => ['origine-mini']
        ];

        /*$default_settings['global_meta_json_data'] = [
            'title'       => __('settings-option-global-jsondata-title'),
            'description' => __('settings-option-global-jsondata-description'),
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['global', 'advanced'],
            'theme'       => ['origine', 'origine-mini']
        ];*/

        // Header.
        $default_settings['header_align'] = [
            'title'       => __('settings-option-header-align-title'),
            'description' => '',
            'type'        => 'select',
            'choices'     => [
                __('settings-option-header-align-left-default') => 'left',
                __('settings-option-header-align-center')       => 'center'
            ],
            'default'     => 'left',
            'section'     => ['header', 'layout'],
            'theme'       => ['origine']
        ];

        $default_settings['header_logo_url'] = [
            'title'       => __('settings-option-header-logourl-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['header', 'logo'],
            'theme'       => ['origine']
        ];

        $default_settings['header_logo_url_2x'] = [
            'title'       => __('settings-option-header-logourl2-title'),
            'description' => __('settings-option-header-logourl2-description'),
            'type'        => 'text',
            'default'     => '',
            'section'     => ['header', 'logo'],
            'theme'       => ['origine']
        ];

        $default_settings['content_post_list_type'] = [
            'title'       => __('settings-option-content-postlisttype-title'),
            'description' => '',
            'type'        => 'select',
            'choices'     => [
                __('settings-option-content-postlisttype-standard-default') => 'standard',
                __('settings-option-content-postlisttype-oneline')          => 'short',
                __('settings-option-content-postlisttype-full')             => 'full'
            ],
            'default'     => 'standard',
            'section'     => ['content', 'post-list'],
            'theme'       => ['origine']
        ];

        $default_settings['content_post_list_first_image'] = [
            'title'       => __('settings-option-content-firstimage-title'),
            'description' => '',
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['content', 'post-list'],
            'theme'       => ['origine']
        ];

        $content_text_font_family_choices = [
            __('settings-option-content-fontfamily-title-same-default') => 'same',
            __('settings-option-global-fontfamily-serif')              => 'serif',
            __('settings-option-global-fontfamily-sansserif')          => 'sans-serif',
            __('settings-option-global-fontfamily-mono')               => 'monospace'
        ];

        $default_settings['content_text_font'] = [
            'title'       => __('settings-option-content-fontfamily-title'),
            'description' => '',
            'type'        => 'select',
            'choices'     => $content_text_font_family_choices,
            'default'     => 'same',
            'section'     => ['content', 'text-formatting'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['content_text_align'] = [
            'title'       => __('settings-option-content-textalign-title'),
            'description' => '',
            'type'        => 'select',
            'choices'     => [
                __('settings-option-content-textalign-left-default')     => 'left',
                __('settings-option-content-textalign-justify')          => 'justify',
                __('settings-option-content-textalign-justifynotmobile') => 'justify_not_mobile'
            ],
            'default'     => 'left',
            'section'     => ['content', 'text-formatting'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['content_hyphens'] = [
            'title'       => __('settings-option-content-hyphens-title'),
            'description' => '',
            'type'        => 'select',
            'choices'     => [
                __('settings-option-content-hyphens-disabled-default') => 'disabled',
                __('settings-option-content-hyphens-enabled')          => 'enabled',
                __('settings-option-content-hyphens-enablednotmobile') => 'enabled_not_mobile'
            ],
            'default'     => 'disabled',
            'section'     => ['content', 'text-formatting'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['content_images_wide'] = [
            'title'       => __('settings-option-content-imageswide-title'),
            'description' => __('settings-option-content-imageswide-description'),
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['content', 'images'],
            'theme'       => ['origine-mini']
        ];

        $default_settings['content_post_author_name'] = [
            'title'       => __('settings-option-content-postauthorname-title'),
            'description' => '',
            'type'        => 'select',
            'choices'     => [
                __('settings-option-content-postauthorname-hidden-default') => 'disabled',
                __('settings-option-content-postauthorname-date')           => 'date',
                __('settings-option-content-postauthorname-signature')      => 'signature'
            ],
            'default'     => 'disabled',
            'section'     => ['content', 'author'],
            'theme'       => ['origine']
        ];

        $default_settings['content_post_list_author_name'] = [
            'title'       => __('settings-option-content-postlistauthorname-title'),
            'description' => '',
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['content', 'author'],
            'theme'       => ['origine']
        ];

        $default_settings['content_post_intro'] = [
            'title'       => __('settings-option-content-postintro-title'),
            'description' => __('settings-option-content-postintro-description'),
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['content', 'other'],
            'theme'       => ['origine-mini']
        ];

        $default_settings['content_separator'] = [
            'title'       => __('settings-option-content-separator-title'),
            'description' => __('settings-option-content-separator-description'),
            'type'        => 'text',
            'default'     => '/',
            'section'     => ['content', 'other'],
            'theme'       => ['origine']
        ];

        $default_settings['content_post_list_comment_link'] = [
            'title'       => __('settings-option-content-postlistcommentlink-title'),
            'description' => '',
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['content', 'comments'],
            'theme'       => ['origine-mini']
        ];

        $default_settings['content_comment_links'] = [
            'title'       => __('settings-option-content-postcommentfeed-title'),
            'description' => '',
            'type'        => 'checkbox',
            'default'     => 1,
            'section'     => ['content', 'comments'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['content_post_email_author'] = [
            'title'       => __('settings-option-content-privatecomment-title'),
            'description' => sprintf(__('settings-option-content-postlistcommentlink-description'), 'https://plugins.dotaddict.org/dc2/details/signal'),
            'type'        => 'select',
            'choices'     => [
                __('settings-option-content-postlistcommentlink-no-default') => 'disabled',
                __('settings-option-content-postlistcommentlink-open')       => 'comments_open',
                __('settings-option-content-postlistcommentlink-always')     => 'always'
            ],
            'default'     => 'disabled',
            'section'     => ['content', 'comments'],
            'theme'       => ['origine', 'origine-mini']
        ];

        // Widgets.
        $default_settings['widgets_nav_position'] = [
            'title'       => __('settings-option-widgets-navposition-title'),
            'description' => '',
            'type'        => 'select',
            'choices'     => [
                __('settings-option-widgets-navposition-top')            => 'header_content',
                __('settings-option-widgets-navposition-bottom-default') => 'content_footer',
                __('settings-option-widgets-navposition-disabled')       => 'disabled'
            ],
            'default'     => 'content_footer',
            'section'     => ['widgets', 'no-title'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['widgets_search_form'] = [
            'title'       => __('settings-option-widgets-searchform-title'),
            'description' => '',
            'type'        => 'checkbox',
            'default'     => 0,
            'section'     => ['widgets', 'no-title'],
            'theme'       => ['origine-mini']
        ];

        $default_settings['widgets_extra_enabled'] = [
            'title'       => __('settings-option-widgets-extra-title'),
            'description' => '',
            'type'        => 'checkbox',
            'default'     => 1,
            'section'     => ['widgets', 'no-title'],
            'theme'       => ['origine', 'origine-mini']
        ];

        // Footer.
        $default_settings['footer_enabled'] = [
            'title'       => __('settings-option-footer-activation-title'),
            'description' => __('settings-option-footer-activation-description'),
            'type'        => 'checkbox',
            'default'     => 1,
            'section'     => ['footer', 'no-title'],
            'theme'       => ['origine', 'origine-mini'],
        ];

        $default_settings['footer_credits'] = [
            'title'       => __('settings-option-footer-credits-title'),
            'description' => '',
            'type'        => 'checkbox',
            'default'     => 1,
            'section'     => ['footer', 'no-title'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['footer_align'] = [
            'title'       => __('settings-option-footer-align-title'),
            'description' => '',
            'type'        => 'select',
            'choices'     => [
                __('settings-option-footer-align-left-default') => 'left',
                __('settings-option-footer-align-center')       => 'center'
            ],
            'default'     => 'left',
            'section'     => ['footer', 'no-title'],
            'theme'       => ['origine', 'origine-mini']
        ];

        $default_settings['footer_social_links_diaspora'] = [
            'title'       => __('settings-option-footer-sociallinks-diaspora-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['footer_social_links_discord'] = [
            'title'       => __('settings-option-footer-sociallinks-discord-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['footer_social_links_facebook'] = [
            'title'       => __('settings-option-footer-sociallinks-facebook-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['footer_social_links_github'] = [
            'title'       => __('settings-option-footer-sociallinks-github-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['footer_social_links_mastodon'] = [
            'title'       => __('settings-option-footer-sociallinks-mastodon-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['footer_social_links_signal'] = [
            'title'       => __('settings-option-footer-sociallinks-signal-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['footer_social_links_tiktok'] = [
            'title'       => __('settings-option-footer-sociallinks-tiktok-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['footer_social_links_twitter'] = [
            'title'       => __('settings-option-footer-sociallinks-twitter-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['footer_social_links_whatsapp'] = [
            'title'       => __('settings-option-footer-sociallinks-whatsapp-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => ['footer', 'social-links'],
            'theme'       => ['origine']
        ];

        $default_settings['css_origine'] = [
            'title'       => __('settings-option-footer-originestyles-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => [],
            'theme'       => ['origine']
        ];

        $default_settings['css_origine_mini'] = [
            'title'       => __('settings-option-footer-origineministyles-title'),
            'description' => '',
            'type'        => 'text',
            'default'     => '',
            'section'     => [],
            'theme'       => ['origine-mini']
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
            'global_css',
            'global_separator',
            'settings-option-global-pagewidth-title-480-default',
            'settings-option-global-pagewidth-title-560',
            'settings-option-global-pagewidth-title-640',
        ];

        return $settings_to_unset;
    }
}
