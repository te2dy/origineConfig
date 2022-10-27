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

/**
 * Converts an array to CSS without spaces and line breaks.
 * 
 * @param array $rules An array of CSS rules.
 * 
 * @return string $css All the CSS in a single line.
 */
function origineConfigArrayToCSS($rules)
{
    $css = '';

    foreach ($rules as $key => $value) {
        if (is_array($value) && !empty($value)) {
            $selector     = $key;
            $properties = $value;

            $css .= str_replace(', ', ',', $selector) . '{';

            if (is_array($properties) && !empty($properties)) {
                foreach ($properties as $property => $rule) {
                    $css .= $property . ':' . str_replace(', ', ',', $rule) . ';';
                }
            }

            $css .= '}';
        }
    }

    return $css;
}

/**
 * Displays the input of a setting.
 * 
 * @param string $setting_id       The ID of the setting to display.
 * @param array  $default_settings All default settings.
 * @param array  $settings         All values of settings set by the user.
 * 
 * @return void
 */
function origineConfigSettingDisplay($setting_id = '', $default_settings = [], $settings = [])
{
    if ($setting_id !== '' && !empty($settings) && !empty($default_settings) && array_key_exists($setting_id, $default_settings) === true) {
        echo '<p>';

        if ($default_settings[$setting_id]['type'] === 'checkbox') {
            echo form::checkbox(
                $setting_id,
                true,
                $settings[$setting_id]
            ),
            '<label class=classic for=' . $setting_id . '>',
            $default_settings[$setting_id]['title'],
            '</label>';
        } elseif ($default_settings[$setting_id]['type'] === 'select' || $default_settings[$setting_id]['type'] === 'select_int') {
            echo '<label for="' . $setting_id . '">',
            $default_settings[$setting_id]['title'],
            '</label>',
            form::combo(
                $setting_id,
                $default_settings[$setting_id]['choices'],
                strval($settings[$setting_id])
            );
        } elseif ($default_settings[$setting_id]['type'] === 'text') {
            echo '<label for="' . $setting_id . '">',
            $default_settings[$setting_id]['title'],
            '</label>',
            form::field(
                $setting_id,
                30,
                255,
                $settings[$setting_id]
            );
        }

        echo '</p>';

        // If the setting has a description, displays it as a note.
        if ($default_settings[$setting_id]['type'] === 'checkbox' || (isset($default_settings[$setting_id]['description']) && $default_settings[$setting_id]['description'] !== '')) {
            echo '<p class=form-note>', $default_settings[$setting_id]['description'];

            if ($default_settings[$setting_id]['type'] === 'checkbox') {
                if ($default_settings[$setting_id]['default'] === 1) {
                    echo ' ', __('option-default-checked');
                } else {
                    echo ' ', __('option-default-unchecked');
                }
            }

            echo '</p>';
        }
    }
}

/**
 * Checks if the theme supports the current option.
 * 
 * @param string       The current theme id.
 * @param string|array Themes thats supports the option.
 * 
 * @return bool True if the theme supports the option to display.
 */
function option_supported($theme_current = '', $theme_supported = '')
{
    if ($theme_supported && (is_array($theme_supported) && in_array($theme_current, $theme_supported, true))) {
        return true;
    } else {
        return false;
    }
}

$theme = \dcCore::app()->blog->settings->system->theme;

$default_settings = origineConfigSettings::default_settings();

\dcCore::app()->blog->settings->addNamespace('origineConfig');

// Adds all default settings values if necessary.
foreach($default_settings as $setting_id => $setting_data) {
    if (!\dcCore::app()->blog->settings->origineConfig->$setting_id) {
        if ($setting_data['type'] === 'checkbox') {
            $setting_type = 'boolean';
        } elseif ($setting_data['type'] === 'select_int') {
            $setting_type = 'integer';
        } else {
            $setting_type = 'string';
        }

        \dcCore::app()->blog->settings->origineConfig->put(
            $setting_id,
            $setting_data['default'],
            $setting_type,
            $setting_data['title'],
            false
        );
    }
}

// An array or all settings.
$settings = [];

foreach($default_settings as $setting_id => $setting_data) {
    if ($setting_data['type'] === 'checkbox') {
        $settings[$setting_id] = (boolean) \dcCore::app()->blog->settings->origineConfig->$setting_id;
    } elseif ($setting_data['type'] === 'select_int') {
        $settings[$setting_id] = (integer) \dcCore::app()->blog->settings->origineConfig->$setting_id;
    } else {
        $settings[$setting_id] = \dcCore::app()->blog->settings->origineConfig->$setting_id;
    }
}

if (!empty($_POST)) {
    try {
        if (isset($_POST['default']) === false) {
            // Ignores styles.
            $settings_to_ignore = ['css_origine', 'css_origine_mini'];

            // Saves options.
            foreach ($settings as $id => $value) {
                if (option_supported($theme, $default_settings[$id]['theme']) === true && !in_array($id, $settings_to_ignore)) {
                    if ($default_settings[$id]['type'] === 'checkbox') {
                        if (!empty($_POST[$id]) && intval($_POST[$id]) === 1) {
                            \dcCore::app()->blog->settings->origineConfig->put($id, true);
                        } else {
                            \dcCore::app()->blog->settings->origineConfig->put($id, false);
                        }
                    } elseif (isset($_POST[$id])) {
                        \dcCore::app()->blog->settings->origineConfig->put($id, trim(html::escapeHTML($_POST[$id])));
                    }
                }
            }

        // Resets options.
        } else {
            foreach($default_settings as $setting_id => $setting_data) {
                if ($setting_data['type'] === 'checkbox') {
                    $setting_type = 'boolean';
                } elseif ($setting_data['type'] === 'select_int') {
                    $setting_type = 'integer';
                } else {
                    $setting_type = 'string';
                }

                \dcCore::app()->blog->settings->origineConfig->put(
                    $setting_id,
                    $setting_data['default'],
                    $setting_type,
                    $setting_data['title'],
                    true
                );
            }
        }

        /**
         * Saves custom styles.
         *
         * Put all styles in a array ($css_array)
         * to save then in the database as a string ($css) with put()
         * formatted via the function origineConfigArrayToCSS().
         */
        $theme = \dcCore::app()->blog->settings->system->theme;

        $css = '';

        $css_root_array           = [];
        $css_root_media_array     = [];
        $css_main_array           = [];
        $css_media_array          = [];
        $css_media_contrast_array = [];
        $css_media_motion_array   = [];

        // Page width.
        $page_width_allowed = [30, 35, 40];

        if (isset($_POST['global_page_width'])) {
            if (in_array(intval($_POST['global_page_width']), $page_width_allowed, true)) {
                $css_root_array[':root']['--page-width'] = $_POST['global_page_width'] . 'em';
            } else {
                $css_root_array[':root']['--page-width'] = '30em';
            }
        }

        // Sets the order of site elements.
        $structure_order = [2 => '',];

        if (isset($_POST['widgets_nav_position']) && $_POST['widgets_nav_position'] === 'header_content') {
            $structure_order[2] = '--order-widgets-nav';
        }

        if ($structure_order[2] === '') {
            $structure_order[2] = '--order-content';
        } else {
            $structure_order[] = '--order-content';
        }

        if (isset($_POST['widgets_nav_position']) && $_POST['widgets_nav_position'] === 'content_footer') {
            $structure_order[] = '--order-widgets-nav';
        }

        if (isset($_POST['widgets_extra_enabled']) && $_POST['widgets_extra_enabled'] === '1') {
            $structure_order[] = '--order-widgets-extra';
        }

        if (isset($_POST['footer_enabled']) && $_POST['footer_enabled'] === '1') {
            $structure_order[] = '--order-footer';
        }

        $css_root_array[':root']['--order-content'] = array_search('--order-content', $structure_order);

        if (in_array('--order-widgets-nav', $structure_order, true)) {
            $css_root_array[':root']['--order-widgets-nav'] = array_search('--order-widgets-nav', $structure_order);
        }

        if (in_array('--order-widgets-extra', $structure_order, true)) {
            $css_root_array[':root']['--order-widgets-extra'] = array_search('--order-widgets-extra', $structure_order);
        }

        if (in_array('--order-footer', $structure_order, true)) {
            $css_root_array[':root']['--order-footer'] = array_search('--order-footer', $structure_order);
        }

        // Text align
        if (isset($_POST['content_text_align'])) {
            if ($_POST['content_text_align'] === 'justify' || $_POST['content_text_align'] === 'justify_not_mobile') {
                $css_root_array[':root']['--text-align'] = 'justify';
            }
        }

        $font_serif = '"Iowan Old Style", "Apple Garamond", Baskerville, "Times New Roman", "Droid Serif", Times, "Source Serif Pro", serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';

        $font_sans_serif = '-apple-system, BlinkMacSystemFont, "Avenir Next", Avenir, "Segoe UI", "Helvetica Neue", Helvetica, Ubuntu, Roboto, Noto, Arial, sans-serif';

        $font_mono = 'Menlo, Consolas, Monaco, "Liberation Mono", "Lucida Console", monospace';

        // Font family.
        if (isset($_POST['global_font_family'])) {
            if ($_POST['global_font_family'] === 'serif') {
                $css_root_array[':root']['--font-family'] = $font_serif;
            } elseif ($_POST['global_font_family'] === 'sans-serif') {
                $css_root_array[':root']['--font-family'] = $font_sans_serif;
            } else {
                $css_root_array[':root']['--font-family'] = $font_mono;
            }
        }

        // Font size.
        if (isset($_POST['global_font_size']) === true && intval($_POST['global_font_size']) > 0) {
            $css_root_array[':root']['--font-size'] = ($_POST['global_font_size'] / 100) . 'em';
        }

        if ($theme === 'origine') {
            $link_colors = [
                'red'        => [
                    'light' => '#de0000',
                    'dark'  => '#f14646'
                ],
                'blue'     => [
                    'light' => '#0057b7',
                    'dark'  => '#529ff5'
                ],
                'green'    => [
                    'light' => '#006400',
                    'dark'  => '#18af18'
                ],
                'orange' => [
                    'light' => '#ff8c00',
                    'dark'  => '#ffab2e'
                ],
                'purple' => [
                    'light' => '#800080',
                    'dark'  => '#9a389a'
                ]
            ];

            if (isset($_POST['global_color_secondary']) && array_key_exists($_POST['global_color_secondary'], $link_colors)) {
                $the_color = $_POST['global_color_secondary'];
            } else {
                $the_color = 'red';
            }

            if (isset($_POST['global_color_scheme'])) {
                if ($_POST['global_color_scheme'] === 'system') {
                    $css_root_array[':root']['--color-background']             = '#fff';
                    $css_root_array[':root']['--color-text-primary']           = '#000';
                    $css_root_array[':root']['--color-text-secondary']         = '#595959';
                    $css_root_array[':root']['--color-link']                   = $link_colors[$the_color]['light'];
                    $css_root_array[':root']['--color-border']                 = '#aaa';
                    $css_root_array[':root']['--color-input-text']             = '#000';
                    $css_root_array[':root']['--color-input-text-hover']       = '#fff';
                    $css_root_array[':root']['--color-input-background']       = '#eaeaea';
                    $css_root_array[':root']['--color-input-background-hover'] = '#000';

                    $css_root_media_array                                            = [];
                    $css_root_media_array[':root']['--color-background']             = '#16161d';
                    $css_root_media_array[':root']['--color-text-primary']           = '#d9d9d9';
                    $css_root_media_array[':root']['--color-text-secondary']         = '#8c8c8c';
                    $css_root_media_array[':root']['--color-link']                   = $link_colors[$the_color]['dark'];
                    $css_root_media_array[':root']['--color-border']                 = '#aaa';
                    $css_root_media_array[':root']['--color-input-text']             = '#d9d9d9';
                    $css_root_media_array[':root']['--color-input-text-hover']       = '#16161d';
                    $css_root_media_array[':root']['--color-input-background']       = '#333333';
                    $css_root_media_array[':root']['--color-input-background-hover'] = '#d9d9d9';
                } elseif ($_POST['global_color_scheme'] === 'dark') {
                    $css_root_array[':root']['--color-background']            = '#16161d';
                    $css_root_array[':root']['--color-text-primary']          = '#d9d9d9';
                    $css_root_array[':root']['--color-text-secondary']         = '#8c8c8c';
                    $css_root_array[':root']['--color-link']                   = $link_colors[$the_color]['dark'];
                    $css_root_array[':root']['--color-border']                 = '#aaa';
                    $css_root_array[':root']['--color-input-text']             = '#d9d9d9';
                    $css_root_array[':root']['--color-input-text-hover']       = '#16161d';
                    $css_root_array[':root']['--color-input-background']       = '#333333';
                    $css_root_array[':root']['--color-input-background-hover'] = '#d9d9d9';
                } else {
                    $css_root_array[':root']['--color-background']             = '#fff';
                    $css_root_array[':root']['--color-text-primary']           = '#000';
                    $css_root_array[':root']['--color-text-secondary']         = '#595959';
                    $css_root_array[':root']['--color-link']                   = $link_colors[$the_color]['light'];
                    $css_root_array[':root']['--color-border']                 = '#aaa';
                    $css_root_array[':root']['--color-input-text']             = '#000';
                    $css_root_array[':root']['--color-input-text-hover']       = '#fff';
                    $css_root_array[':root']['--color-input-background']       = '#eaeaea';
                    $css_root_array[':root']['--color-input-background-hover'] = '#000';
                }
            }
        } elseif ($theme === 'origine-mini') {
            // Secondary color hue.
            $secondary_colors_allowed = [
                'blue' => [
                    /**
                     * HSL recipe:
                     * --color-primary: 226, 80%, 45%
                     * --color-background: 226, 10%, 99%
                     * --color-text-main: 226, 10%, 20%;
                     * --color-text-secondary: 226, 10%, 45%;
                     * --color-border: 226, 20%, 80%;
                     * --color-input-background: 226, 10%, 95%;
                     */
                    '--color-primary'          => '#1742cf',
                    '--color-background'       => '#fcfcfd',
                    '--color-text-main'        => '#2e3038',
                    '--color-text-secondary'   => '#676d7e',
                    '--color-border'           => '#c2c7d6',
                    '--color-input-background' => '#f1f2f4'
                ],

                'gray' => [
                    /**
                     * HSL recipe:
                     * --color-primary: 0, 0%, 10%;
                     * --color-background: 0, 0%, 99%;
                     * --color-text-main: 0, 0%, 20%;
                     * --color-text-secondary: 0, 0%, 50%;
                     * --color-border: 0, 0%, 80%;
                     * --color-input-background: 0, 0%, 95%;
                     */
                    '--color-primary'          => '#1a1a1a',
                    '--color-background'       => '#fcfcfc',
                    '--color-text-main'        => '#333333',
                    '--color-text-secondary'   => '#808080',
                    '--color-border'           => '#cccccc',
                    '--color-input-background' => '#f2f2f2'
                ],

                'green' => [
                    /**
                     * HSL recipe:
                     * --color-primary: 120, 75%, 30%;
                     * --color-background: 120, 10%, 99%;
                     * --color-text-main: 120, 10%, 20%;
                     * --color-text-secondary: 120, 5%, 50%;
                     * --color-border: 120, 20%, 80%;
                     * --color-input-background: 120, 10%, 95%;
                     */
                    '--color-primary'          => '#138613',
                    '--color-background'       => '#fcfcfc',
                    '--color-text-main'        => '#2e382e',
                    '--color-text-secondary'   => '#798679',
                    '--color-border'           => '#c2d6c2',
                    '--color-input-background' => '#f1f4f1'
                ],

                'red' => [
                    /**
                     * HSL recipe:
                     * --color-primary: 0, 80%, 50%;
                     * --color-background: 0, 10%, 99%;
                     * --color-text-main: 0, 10%, 20%;
                     * --color-text-secondary: 0, 5%, 50%;
                     * --color-border: 0, 20%, 80%;
                     * --color-input-background: 0, 10%, 95%;
                     */
                    '--color-primary'          => '#e61919',
                    '--color-background'       => '#fdfcfc',
                    '--color-text-main'        => '#382e2e',
                    '--color-text-secondary'   => '#867979',
                    '--color-border'           => '#d6c2c2',
                    '--color-input-background' => '#f4f1f1'
                ]
            ];

            if (isset($_POST['global_color_secondary'])) {
                if (array_key_exists($_POST['global_color_secondary'], $secondary_colors_allowed)) {
                    foreach ($secondary_colors_allowed[$_POST['global_color_secondary']] as $key => $value) {
                        $css_root_array[':root'][$key] = $value;
                    }
                } else {
                    foreach ($secondary_colors_allowed['blue'] as $key => $value) {
                        $css_root_array[':root'][$key] = $value;
                    }
                }
            }
        }

        /**
         * Main styles.
         */

        // Header alignment.
        if ($theme === 'origine' && isset($_POST['header_align']) && $_POST['header_align'] === 'center') {
            $css_main_array['#site-header']['text-align'] = 'center';
        }

        if ($theme === 'origine' && isset($_POST['header_logo_url']) && $_POST['header_logo_url'] !== '') {
            $css_main_array['.site-logo-container']['margin-bottom'] = '.5rem';
        }

        // Transitions.
        if ($theme = 'origine') {
            if (isset($_POST['global_css_transition']) && $_POST['global_css_transition'] === '1') {
                $css_main_array['a']['transition']                          = 'all .2s ease-in-out';
                $css_main_array['a:active, a:focus, a:hover']['transition'] = 'all .2s ease-in-out';

                $css_main_array['a .post-meta, a .post-excerpt']['transition']             = 'all .2s ease-in-out';
                $css_main_array['a:hover .post-meta, a:hover .post-excerpt']['transition'] = 'all .2s ease-in-out';

                $css_main_array['input[type="submit"], .form-submit, .button']['transition'] = 'all .2s ease-in-out';

                $css_main_array['input[type="submit"]:active, input[type="submit"]:focus, input[type="submit"]:hover, .button:active, .button:focus, .button:hover, .form-submit:active, .form-submit:focus, .form-submit:hover']['transition'] = 'all .2s ease-in-out';
            }

        } elseif ($theme = 'origine-mini') {
            if (isset($_POST['global_css_transition']) && $_POST['global_css_transition'] === '1') {
                $css_main_array['a']['transition']                 = 'all .2s ease-in-out';
                $css_main_array['a:active, a:hover']['transition'] = 'all .2s ease-in-out';

                $css_main_array['input[type="submit"], .form-submit, .button']['transition'] = 'all .2s ease-in-out';

                $css_main_array['input[type="submit"]:hover, .button:hover, .form-submit:hover']['transition'] = 'all .2s ease-in-out';
            }
        }

        // Border radius.
        if (isset($_POST['global_css_border_radius']) && $_POST['global_css_border_radius'] === '1') {
            $css_main_array['#site-title,.button,.post-selected,button,code,input,pre,textarea']['border-radius'] = '.168rem';
        }

        // Font family of content.
        if (isset($_POST['content_text_font']) && $_POST['content_text_font'] !== 'same' && $_POST['global_font_family'] !== $_POST['content_text_font']) {
            if ($_POST['content_text_font'] === 'serif') {
                $css_main_array['.content-text']['font-family'] = $font_serif;
            } elseif ($_POST['content_text_font'] === 'sans-serif') {
                $css_main_array['.content-text']['font-family'] = $font_sans_serif;
            } else {
                $css_main_array['.content-text']['font-family'] = $font_mono;
            }
        }

        // Hyphens.
        if (isset($_POST['content_hyphens']) && $_POST['content_hyphens'] !== 'disabled') {
            $css_main_array['.content-text']['-webkit-hyphens'] = 'auto';
            $css_main_array['.content-text']['-moz-hyphens']    = 'auto';
            $css_main_array['.content-text']['-ms-hyphens']     = 'auto';
            $css_main_array['.content-text']['hyphens']         = 'auto';

            $css_main_array['.content-text']['-webkit-hyphenate-limit-chars'] = '5 2 2';
            $css_main_array['.content-text']['-moz-hyphenate-limit-chars']    = '5 2 2';
            $css_main_array['.content-text']['-ms-hyphenate-limit-chars']     = '5 2 2';

            $css_main_array['.content-text']['-moz-hyphenate-limit-lines'] = '2';
            $css_main_array['.content-text']['-ms-hyphenate-limit-lines']  = '2';
            $css_main_array['.content-text']['hyphenate-limit-lines']      = '2';

            $css_main_array['.content-text']['-webkit-hyphenate-limit-last'] = 'always';
            $css_main_array['.content-text']['-moz-hyphenate-limit-last']    = 'always';
            $css_main_array['.content-text']['-ms-hyphenate-limit-last']     = 'always';
            $css_main_array['.content-text']['hyphenate-limit-last']         = 'always';
        }

        // Link to reactions in the post list.
        if (isset($_POST['content_post_list_comment_link']) && $_POST['content_post_list_comment_link'] === '1') {
            $css_main_array['.post-comment-link']['margin-right'] = '.2rem';
        }

        // Social links.
        if (isset($_POST['footer_social_links_diaspora']) || isset($_POST['footer_social_links_discord']) || isset($_POST['footer_social_links_facebook']) || isset($_POST['footer_social_links_github']) || isset($_POST['footer_social_links_mastodon']) || isset($_POST['footer_social_links_signal']) || isset($_POST['footer_social_links_tiktok']) || isset($_POST['footer_social_links_twitter']) || isset($_POST['footer_social_links_whatsapp'])) {
          $css_main_array['.footer-social-links ul']['list-style']                 = 'none';
          $css_main_array['.footer-social-links ul']['margin']                     = '0';
          $css_main_array['.footer-social-links ul']['padding-left']               = '0';
          $css_main_array['.footer-social-links ul li']['display']                 = 'inline-block';
          $css_main_array['.footer-social-links ul li']['margin']                  = '.25em';
          $css_main_array['.footer-social-links ul li:first-child']['margin-left'] = '0';
          $css_main_array['.footer-social-links ul li:last-child']['margin-right'] = '0';

          $css_main_array['.footer-social-links a']['display'] = 'inline-block';

          $css_main_array['.footer-social-links-icon-container']['align-items']      = 'center';
          $css_main_array['.footer-social-links-icon-container']['background-color'] = 'var(--color-input-background)';
          $css_main_array['.footer-social-links-icon-container']['border-radius']    = '0.125rem';
          $css_main_array['.footer-social-links-icon-container']['display']          = 'flex';
          $css_main_array['.footer-social-links-icon-container']['justify-content']  = 'center';
          $css_main_array['.footer-social-links-icon-container']['width']            = '1.5rem';
          $css_main_array['.footer-social-links-icon-container']['height']           = '1.5rem';

          $css_main_array['.footer-social-links-icon']['border']          = '0';
          $css_main_array['.footer-social-links-icon']['fill']            = 'var(--color-input-text)';
          $css_main_array['.footer-social-links-icon']['stroke']          = 'none';
          $css_main_array['.footer-social-links-icon']['stroke-linecap']  = 'round';
          $css_main_array['.footer-social-links-icon']['stroke-linejoin'] = 'round';
          $css_main_array['.footer-social-links-icon']['stroke-width']    = '0';
          $css_main_array['.footer-social-links-icon']['width']           = '1rem';

          $css_main_array['.footer-social-links a:active .footer-social-links-icon-container, .footer-social-links a:focus .footer-social-links-icon-container, .footer-social-links a:hover .footer-social-links-icon-container']['background-color'] = 'var(--color-input-background-hover)';

          $css_main_array['.footer-social-links a']['border-bottom'] = 'none';

          $css_main_array['.footer-social-links a:active, .footer-social-links a:focus, .footer-social-links a:hover']['border-bottom'] = 'none';

          $css_main_array['.footer-social-links a:active .footer-social-links-icon, .footer-social-links a:focus .footer-social-links-icon, .footer-social-links a:hover .footer-social-links-icon']['fill'] = 'var(--color-input-text-hover)';

          if (isset($_POST['global_css_transition']) && $_POST['global_css_transition'] === true) {
            $css_main_array['.footer-social-links-icon-container']['transition'] = 'all .2s ease-in-out';
            $css_main_array['.footer-social-links-icon']['transition'] = 'all .2s ease-in-out';
            $css_main_array['.footer-social-links a:active .footer-social-links-icon-container, .footer-social-links a:focus .footer-social-links-icon-container, .footer-social-links a:hover .footer-social-links-icon-container']['transition'] = 'all .2s ease-in-out';
            $css_main_array['.footer-social-links a:active .footer-social-links-icon, .footer-social-links a:focus .footer-social-links-icon, .footer-social-links a:hover .footer-social-links-icon']['transition'] = 'all .2s ease-in-out';
          }

          $css_media_contrast_array['.footer-social-links-icon-container']['border'] = '1px solid var(--color-border)';
        }

        /**
         * Media queries.
         */

        // Text alignment.
        if (isset($_POST['content_text_align']) && $_POST['content_text_align'] === 'justify_not_mobile') {
            $css_media_array[':root']['--text-align'] = 'left';
        }

        // Hyphenation.
        if (isset($_POST['content_hyphens']) && $_POST['content_hyphens'] === 'enabled_not_mobile') {
            $css_media_array['.content-text']['-webkit-hyphens'] = 'unset';
            $css_media_array['.content-text']['-moz-hyphens']    = 'unset';
            $css_media_array['.content-text']['-ms-hyphens']     = 'unset';
            $css_media_array['.content-text']['hyphens']         = 'unset';

            $css_media_array['.content-text']['-webkit-hyphenate-limit-chars'] = 'unset';
            $css_media_array['.content-text']['-moz-hyphenate-limit-chars']    = 'unset';
            $css_media_array['.content-text']['-ms-hyphenate-limit-chars']     = 'unset';

            $css_media_array['.content-text']['-moz-hyphenate-limit-lines'] = 'unset';
            $css_media_array['.content-text']['-ms-hyphenate-limit-lines']  = 'unset';
            $css_media_array['.content-text']['hyphenate-limit-lines']      = 'unset';

            $css_media_array['.content-text']['-webkit-hyphenate-limit-last'] = 'unset';
            $css_media_array['.content-text']['-moz-hyphenate-limit-last']    = 'unset';
            $css_media_array['.content-text']['-ms-hyphenate-limit-last']     = 'unset';
            $css_media_array['.content-text']['hyphenate-limit-last']         = 'unset';
        }

        // Reduced motion.
        if (isset($_POST['global_css_transition']) && $_POST['global_css_transition'] === '1') {
            $css_media_motion_array['a']['transition']                 = 'none';
            $css_media_motion_array['a:active, a:hover']['transition'] = 'none';

            $css_media_motion_array['input[type="submit"], .form-submit, .button']['transition']                   = 'none';
            $css_media_motion_array['input[type="submit"]:hover, .button:hover, .form-submit:hover']['transition'] = 'none';
        }

        $css .= !empty($css_root_array) ? origineConfigArrayToCSS($css_root_array) : '';
        $css .= !empty($css_root_media_array) ? ' @media (prefers-color-scheme:dark){' . origineConfigArrayToCSS($css_root_media_array) . '}' : '';
        $css .= !empty($css_main_array) ? origineConfigArrayToCSS($css_main_array) : '';
        $css .= !empty($css_media_array) ? ' @media (max-width: 34em){' . origineConfigArrayToCSS($css_media_array) . '}' : '';
        $css .= !empty($css_media_contrast_array) ? ' @media (prefers-contrast: more), (prefers-contrast: less), (-ms-high-contrast: active), (-ms-high-contrast: black-on-white){' . origineConfigArrayToCSS($css_media_contrast_array) . '}' : '';
        $css .= !empty($css_media_motion_array) ? ' @media (prefers-reduced-motion:reduce){' . origineConfigArrayToCSS($css_media_motion_array) . '}' : '';

        \dcCore::app()->blog->settings->origineConfig->put(
            'css_' . str_replace('-', '_', \dcCore::app()->blog->settings->system->theme),
            htmlspecialchars($css, ENT_NOQUOTES)
        );

        \dcCore::app()->blog->triggerBlog();

        // Clears template cache.
        if (\dcCore::app()->blog->settings->system->tpl_use_cache === true) {
            \dcCore::app()->emptyTemplatesCache();
        }

        dcPage::addSuccessNotice(__('settings-saved-message'));
        http::redirect(\dcCore::app()->admin->getPageURL());
    } catch (Exception $e) {
        \dcCore::app()->error->add($e->getMessage());
    }
}
?>
<html>
    <head>
        <title><?php echo __('admin-title'); ?></title>
    </head>

    <body>
        <?php
        echo dcPage::breadcrumb(
            [
                html::escapeHTML(\dcCore::app()->blog->name) => '',
                __('admin-title')                            => ''
            ]
        );

        echo dcPage::notices();

        $themes_allowed = ['origine', 'origine-mini'];

        if (!in_array($theme, $themes_allowed, true)) :
            ?>
                <p>
                    <?php
                    printf(
                        __('admin-access-denied-origine-only'),
                        html::escapeURL(\dcCore::app()->adminurl->get('admin.blog.theme'))
                    );
                    ?>
                </p>
            <?php
        else :
        ?>
            <form action=<?php echo \dcCore::app()->admin->getPageURL(); ?> method=post>
                <!-- # Displays the activation checkbox before all other settings. -->
                <p>
                    <?php echo form::checkbox('active', true, $settings['active']); ?>

                    <label class=classic for=active><?php echo $default_settings['active']['title']; ?></label>
                </p>

                <p class=form-note>
                    <?php
                    echo $default_settings['active']['description'],
                    ' ',
                    __('option-default-checked');
                    ?>
                </p>

                <?php unset($default_settings['active']); ?>

                <?php
                /**
                 * Creates an array which will contain all the settings and there title following the pattern below.
                 *
                 * $setting_page_content = [
                 *     'section_id_1' => [
                 *         'sub_section_id_1' => ['option_id_1', 'option_id_2'],
                 *         'sub_section_id_2' => ['option_id_3', 'option_id_4'],
                 *         […]
                 *     ],
                 * ];
                 */
                $setting_page_content = [];

                // Gets all setting sections.
                $sections = origineConfigSettings::setting_sections();

                // Puts titles in the settings array.
                foreach($sections as $section_id => $section_data) {
                    $setting_page_content[$section_id] = [];
                }

                $settings_to_ignore = ['css_origine', 'css_origine_mini'];

                // Puts all settings in their sections.
                foreach($default_settings as $setting_id => $setting_data) {
                    if (option_supported($theme, $setting_data['theme']) && !in_array($setting_id, $settings_to_ignore, true)) {
                        if (isset($setting_data['section']) && is_array($setting_data['section'])) {
                            if (isset($setting_data['section'][1])) {
                                $setting_page_content[$setting_data['section'][0]][$setting_data['section'][1]][] = $setting_id;
                            } else {
                                $setting_page_content[$setting_data['section'][0]][] = $setting_id;
                            }
                        } elseif (isset($setting_data['section']) && is_string($setting_data['section'])) {
                            $setting_page_content[$setting_data['section'][0]][] = $setting_id;
                        }
                    }
                }

                // Removes titles when there are associated with any setting.
                $setting_page_content = array_filter($setting_page_content);

                // Displays the title of each sections and put the settings inside.
                foreach ($setting_page_content as $title_id => $section_content) {
                    echo '<h3>',
                    $sections[$title_id]['name'],
                    '</h3>';

                    foreach ($section_content as $sub_section_id => $setting_id) {
                        echo '<div class=fieldset>';

                        // Shows the sub section name, except if its ID is "no-title".
                        if (is_string($sub_section_id) && $sub_section_id !== 'no-title') {
                            echo '<h4>',
                            $sections[$title_id]['sub_sections'][$sub_section_id],
                            '</h4>';
                        }

                        // Displays the option.
                        if (is_string($setting_id)) {
                            echo origineConfigSettingDisplay($setting_id, $default_settings, $settings);
                        } else {
                            foreach ($setting_id as $setting_id_value) {
                                echo origineConfigSettingDisplay($setting_id_value, $default_settings, $settings);
                            }
                        }

                        echo '</div>';
                    }
                }
                ?>

                <p>
                    <?php echo \dcCore::app()->formNonce(); ?>

                    <input type=submit value="<?php echo __('admin-save-button-text'); ?>"> <input class=delete name=default type=submit value="<?php echo __('admin-reset-button-text'); ?>">
                </p>

                <p class=form-note>
                    <?php
                    printf(
                        __('admin-settings-not-working-message'),
                        '#active'
                    );
                    ?>
                </p>
            </form>
        <?php endif; ?>
    </body>
</html>
