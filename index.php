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
 */
function origineConfigArrayToCSS($rules)
{
  $css = '';

  if ($rules) {
    foreach ($rules as $key => $value) {
      if (is_array($value) && !empty($value)) {
        $selector   = $key;
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
  }

  return $css;
}

$core->blog->settings->addNamespace('origineConfig');

if (is_null($core->blog->settings->origineConfig->origine_settings)) {
  try {
    // Default settings.
    $origine_settings = origineConfigSettings::default_settings();

    $core->blog->settings->origineConfig->put('origine_settings', $origine_settings, 'array', 'All Origine settings', false);

    $core->blog->triggerBlog();
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

$origine_settings = $core->blog->settings->origineConfig->origine_settings;

if (!empty($_POST) && is_array($origine_settings)) {
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

  $core->blog->settings->origineConfig->put('origine_settings', $origine_settings, 'array', 'All Origine settings', false);

  try {
    /**
     * Get settings from the form
     * and escape them.
     */

    // Activation
    $origine_settings['activation'] = !empty($_POST['activation']);

    // Global settings
    $origine_settings['global_color_scheme']   = trim(html::escapeHTML($_POST['global_color_scheme']));
    $origine_settings['global_color_link']     = trim(html::escapeHTML($_POST['global_color_link']));
    $origine_settings['global_css_transition'] = !empty($_POST['global_css_transition']);
    $origine_settings['global_meta_generator'] = !empty($_POST['global_meta_generator']);

    // Header settings
    $origine_settings['header_align']       = trim(html::escapeHTML($_POST['header_align']));
    $origine_settings['header_widgets_nav'] = !empty($_POST['header_widgets_nav']);
    $origine_settings['header_logo_url']    = trim(html::escapeHTML($_POST['header_logo_url']));
    $origine_settings['header_logo_url_2x'] = trim(html::escapeHTML($_POST['header_logo_url_2x']));

    // Content
    $origine_settings['content_post_list_type']        = trim(html::escapeHTML($_POST['content_post_list_type']));
    $origine_settings['content_post_list_first_image'] = !empty($_POST['content_post_list_first_image']);
    $origine_settings['content_font_family']           = trim(html::escapeHTML($_POST['content_font_family']));
    $origine_settings['content_font_size']             = abs((int) $_POST['content_font_size']);
    $origine_settings['content_text_align']            = trim(html::escapeHTML($_POST['content_text_align']));
    $origine_settings['content_hyphens']               = trim(html::escapeHTML($_POST['content_hyphens']));
    $origine_settings['content_post_list_author_name'] = !empty($_POST['content_post_list_author_name']);
    /*
    To enable in the future:
    $origine_settings['content_share_link_email']      = !empty($_POST['content_share_link_email']);
    $origine_settings['content_share_link_facebook']   = !empty($_POST['content_share_link_facebook']);
    $origine_settings['content_share_link_print']      = !empty($_POST['content_share_link_print']);
    $origine_settings['content_share_link_whatsapp']   = !empty($_POST['content_share_link_whatsapp']);
    $origine_settings['content_share_link_twitter']    = !empty($_POST['content_share_link_twitter']);
    */
    $origine_settings['content_comment_links']         = !empty($_POST['content_comment_links']);
    $origine_settings['content_post_email_author']     = trim(html::escapeHTML($_POST['content_post_email_author']));

    // Widgets settings
    $origine_settings['widgets_enabled'] = !empty($_POST['widgets_enabled']);

    // Footer settings
    $origine_settings['footer_enabled']               = !empty($_POST['footer_enabled']);
    $origine_settings['footer_align']                 = trim(html::escapeHTML($_POST['footer_align']));
    $origine_settings['footer_credits']               = !empty($_POST['footer_credits']);
    $origine_settings['footer_social_links_diaspora'] = trim(html::escapeHTML($_POST['footer_social_links_diaspora']));
    $origine_settings['footer_social_links_discord']  = trim(html::escapeHTML($_POST['footer_social_links_discord']));
    $origine_settings['footer_social_links_facebook'] = trim(html::escapeHTML($_POST['footer_social_links_facebook']));
    $origine_settings['footer_social_links_github']   = trim(html::escapeHTML($_POST['footer_social_links_github']));
    $origine_settings['footer_social_links_mastodon'] = trim(html::escapeHTML($_POST['footer_social_links_mastodon']));
    $origine_settings['footer_social_links_signal']   = trim(html::escapeHTML($_POST['footer_social_links_signal']));
    $origine_settings['footer_social_links_tiktok']   = trim(html::escapeHTML($_POST['footer_social_links_tiktok']));
    $origine_settings['footer_social_links_twitter']  = trim(html::escapeHTML($_POST['footer_social_links_twitter']));
    $origine_settings['footer_social_links_whatsapp'] = trim(html::escapeHTML($_POST['footer_social_links_whatsapp']));

    /**
     * Save settings in the database.
     */
    $core->blog->settings->addNamespace('origineConfig');

    /**
     * Creates styles to save.
     */
    $link_colors = [
      'red'    => [
        'light' => '#de0000',
        'dark'  => '#f14646',
      ],
      'blue'   => [
        'light' => '#0057B7',
        'dark'  => '#529ff5',
      ],
      'green'  => [
        'light' => '#006400',
        'dark'  => '#18af18',
      ],
      'orange' => [
        'light' => '#ff8c00',
        'dark'  => '#ffab2e',
      ],
      'purple' => [
        'light' => '#800080',
        'dark'  => '#9a389a',
      ],
    ];

    $the_color = array_key_exists($origine_settings['global_color_link'], $link_colors) ? $origine_settings['global_color_link'] : 'red';

    /**
     * Put all styles in a array ($css_array)
     * to save then in the database as a string ($css)
     * via the function origineConfigArrayToCSS().
     */
    $css       = '';
    $css_array = [];

    if ($origine_settings['global_color_scheme'] === 'system') {
      $css_array[':root']['--color-background']             = '#fff';
      $css_array[':root']['--color-text-primary']           = '#000';
      $css_array[':root']['--color-text-secondary']         = '#595959';
      $css_array[':root']['--color-link']                   = $link_colors[$the_color]['light'];
      $css_array[':root']['--color-border']                 = '#aaa';
      $css_array[':root']['--color-input-text']             = '#000';
      $css_array[':root']['--color-input-text-hover']       = '#fff';
      $css_array[':root']['--color-input-background']       = '#eaeaea';
      $css_array[':root']['--color-input-background-hover'] = '#000';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];

      $css_array[':root']['--color-background']             = '#16161d';
      $css_array[':root']['--color-text-primary']           = '#d9d9d9';
      $css_array[':root']['--color-text-secondary']         = '#8c8c8c';
      $css_array[':root']['--color-link']                   = $link_colors[$the_color]['dark'];
      $css_array[':root']['--color-border']                 = '#aaa';
      $css_array[':root']['--color-input-text']             = '#d9d9d9';
      $css_array[':root']['--color-input-text-hover']       = '#16161d';
      $css_array[':root']['--color-input-background']       = '#333333';
      $css_array[':root']['--color-input-background-hover'] = '#d9d9d9';

      $css       .= '@media (prefers-color-scheme:dark) {' . origineConfigArrayToCSS($css_array) . '}';
      $css_array  = [];
    } elseif ($origine_settings['global_color_scheme'] === 'dark') {
      $css_array[':root']['--color-background']             = '#16161d';
      $css_array[':root']['--color-text-primary']           = '#d9d9d9';
      $css_array[':root']['--color-text-secondary']         = '#8c8c8c';
      $css_array[':root']['--color-link']                   = $link_colors[$the_color]['dark'];
      $css_array[':root']['--color-border']                 = '#aaa';
      $css_array[':root']['--color-input-text']             = '#d9d9d9';
      $css_array[':root']['--color-input-text-hover']       = '#16161d';
      $css_array[':root']['--color-input-background']       = '#333333';
      $css_array[':root']['--color-input-background-hover'] = '#d9d9d9';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    } else {
      $css_array[':root']['--color-background']             = '#fff';
      $css_array[':root']['--color-text-primary']           = '#000';
      $css_array[':root']['--color-text-secondary']         = '#595959';
      $css_array[':root']['--color-link']                   = $link_colors[$the_color]['light'];
      $css_array[':root']['--color-border']                 = '#aaa';
      $css_array[':root']['--color-input-text']             = '#000';
      $css_array[':root']['--color-input-text-hover']       = '#fff';
      $css_array[':root']['--color-input-background']       = '#eaeaea';
      $css_array[':root']['--color-input-background-hover'] = '#000';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Transitions
    if ($origine_settings['global_css_transition'] === true) {
      $css_array['a']['transition']                          = 'all .2s ease-in-out';
      $css_array['a:active, a:focus, a:hover']['transition'] = 'all .2s ease-in-out';

      $css_array['a .post-meta, a .post-excerpt']['transition']             = 'all .2s ease-in-out';
      $css_array['a:hover .post-meta, a:hover .post-excerpt']['transition'] = 'all .2s ease-in-out';

      $css_array['input[type="submit"], .form-submit, .button']['transition'] = 'all .2s ease-in-out';

      $css_array['input[type="submit"]:active, input[type="submit"]:focus, input[type="submit"]:hover, .button:active, .button:focus, .button:hover, .form-submit:active, .form-submit:focus, .form-submit:hover']['transition'] = 'all .2s ease-in-out';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Header alignment
    if ($origine_settings['header_align'] === 'center') {
      $css_array['#site-header']['text-align'] = 'center';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Header alignment
    if ($origine_settings['header_align'] === 'center') {
      $css_array['#site-footer']['text-align'] = 'center';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Logo
    if ($origine_settings['header_logo_url']) {
      $css_array['.site-logo-container']['display']       = 'inline-block';
      $css_array['.site-logo-container']['margin-bottom'] = '1em';

      $css_array['.site-logo-link']['border-bottom'] = 'none';
      $css_array['.site-logo-link']['display']       = 'block';

      $css_array['.site-logo-link:active, .site-logo-link:focus, .site-logo-link:hover']['border-bottom'] = 'none';

      $css_array['.site-logo']['display'] = 'block';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Font family
    if ($origine_settings['content_font_family'] === 'serif') {
      $css_array['body']['font-family'] = '"Iowan Old Style", "Apple Garamond", Baskerville, "Times New Roman", "Droid Serif", Times, "Source Serif Pro", serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';
    } elseif ($origine_settings['content_font_family'] === 'sans-serif') {
      $css_array['body']['font-family'] = '-apple-system, BlinkMacSystemFont, "Avenir Next", Avenir, "Segoe UI", "Helvetica Neue", Helvetica, Ubuntu, Roboto, Noto, Arial, sans-serif';
    } else {
      $css_array['body']['font-family'] = 'Menlo, Consolas, Monaco, "Liberation Mono", "Lucida Console", monospace';
    }

    // Font size
    if ($origine_settings['content_font_size']) {
      $css_array['body']['font-size'] = abs((int) $origine_settings['content_font_size'] / 100) . 'em';
    }

    // Post list appearance
    switch ($origine_settings['content_post_list_type']) {
      case 'standard':
        $css_array['.post-list-standard .post-link']['display'] = 'block';

        $css_array['.post-list-standard .post-meta']['margin-bottom'] = '.25em';

        $css_array['.post-list-standard .post-title']['font-size'] = '1.1em';

        $css_array['.post-list-standard .label-selected']['border-left']   = 'none';
        $css_array['.post-list-standard .label-selected']['margin-left']   = '-1rem';
        $css_array['.post-list-standard .label-selected']['margin-bottom'] = '.5em';

        $css_array['.post-list-standard .post-list-selected-content']['border-left']  = '.063rem solid var(--color-border)';
        $css_array['.post-list-standard .post-list-selected-content']['padding-left'] = '1rem';

        $css_array['.post-list-standard .label-page']['margin-bottom'] = '.5em';

        $css_array['.post-list-standard .post-list-reactions']['display']     = 'inline-block';
        $css_array['.post-list-standard .post-list-reactions']['margin-left'] = '.25em';

        $css_array['.post-list-standard .post-footer']['font-size'] = '.9em';
        $css_array['.post-list-standard .post-footer']['margin-top'] = '.5em';

        $css_array['.post-list-standard .read-more']['border'] = 'none';
        break;

      case 'short':
        $css_array['.post-list-short .label-selected']['display'] = 'inline-block';

        $css_array['.post-list-short .post-meta, .post-list-short .post-title']['font-size'] = '1em';

        $css_array['.post-list-short .post-title']['display'] = 'inline';

        $css_array['.post-list-short .post-author-name']['color'] = 'var(--color-text-secondary)';
        break;

      case 'full':
        $css_array['.post-list-full .label-selected']['margin-bottom'] = '1em';

        $css_array['.post-list-full .label-page']['margin-bottom'] = '1em';
        break;
    }

    $css       .= origineConfigArrayToCSS($css_array);
    $css_array  = [];

    // Text align
    $content_text = '.post-excerpt, .text p, .text ol li, .text ul li';

    if ($origine_settings['content_text_align'] === 'justify') {
      $css_array[$content_text]['text-align'] = 'justify';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    } elseif ($origine_settings['content_text_align'] === 'justify_not_mobile') {
      $css_array[$content_text]['text-align'] = 'justify';

      // min-width: calc(1rem * 20 + (2em * 2) + 1px)) not supported on all browsers.
      $css       .= '@media only screen and (min-width: 384px) {' . origineConfigArrayToCSS($css_array) . '}';
      $css_array  = [];
    }

    // Hyphens
    if ($origine_settings['content_hyphens'] !== 'disabled' ) {
      $css_array[$content_text]['-webkit-hyphens'] = 'auto';
      $css_array[$content_text]['-moz-hyphens']    = 'auto';
      $css_array[$content_text]['-ms-hyphens']     = 'auto';
      $css_array[$content_text]['hyphens']         = 'auto';

      $css_array[$content_text]['-webkit-hyphenate-limit-chars'] = '5 2 2';
      $css_array[$content_text]['-moz-hyphenate-limit-chars']    = '5 2 2';
      $css_array[$content_text]['-ms-hyphenate-limit-chars']     = '5 2 2';

      $css_array[$content_text]['-moz-hyphenate-limit-lines'] = '2';
      $css_array[$content_text]['-ms-hyphenate-limit-lines']  = '2';
      $css_array[$content_text]['hyphenate-limit-lines']      = '2';

      $css_array[$content_text]['-webkit-hyphenate-limit-last'] = 'always';
      $css_array[$content_text]['-moz-hyphenate-limit-last']    = 'always';
      $css_array[$content_text]['-ms-hyphenate-limit-last']     = 'always';
      $css_array[$content_text]['hyphenate-limit-last']         = 'always';

      if ($origine_settings['content_hyphens'] !== 'enabled_not_mobile') {
        $css       .= origineConfigArrayToCSS($css_array);
        $css_array  = [];
      } else {
        // min-width: calc(1rem * 20 + (2em * 2) + 1px)) not supported on all browsers.
        $css       .= '@media only screen and (min-width: 384px) {' . origineConfigArrayToCSS($css_array) . '}';
        $css_array  = [];
      }
    }

    /*
    // Share links. To enable in the future.
    $css_array['.share-links']['margin-top']    = '2em';
    $css_array['.share-links']['margin-bottom'] = '2em';
    */

    $css       .= origineConfigArrayToCSS($css_array);
    $css_array  = [];

    // Footer alignement.
    if ($origine_settings['footer_align'] === 'center') {
      $css_array['#site-footer']['text-align'] = 'center';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Social links.
    if ($origine_settings['footer_social_links_diaspora']
      || $origine_settings['footer_social_links_discord']
      || $origine_settings['footer_social_links_facebook']
      || $origine_settings['footer_social_links_github']
      || $origine_settings['footer_social_links_mastodon']
      || $origine_settings['footer_social_links_signal']
      || $origine_settings['footer_social_links_tiktok']
      || $origine_settings['footer_social_links_twitter']
      || $origine_settings['footer_social_links_whatsapp']
    ) {
      $css_array['.footer-social-links ul']['list-style']                 = 'none';
      $css_array['.footer-social-links ul']['margin']                     = '0';
      $css_array['.footer-social-links ul']['padding-left']               = '0';
      $css_array['.footer-social-links ul li']['display']                 = 'inline-block';
      $css_array['.footer-social-links ul li']['margin']                  = '.25em';
      $css_array['.footer-social-links ul li:first-child']['margin-left'] = '0';
      $css_array['.footer-social-links ul li:last-child']['margin-right'] = '0';

      $css_array['.footer-social-links a']['display'] = 'inline-block';

      $css_array['.footer-social-links-icon-container']['align-items']      = 'center';
      $css_array['.footer-social-links-icon-container']['background-color'] = 'var(--color-input-background)';
      $css_array['.footer-social-links-icon-container']['border-radius']    = '0.125rem';
      $css_array['.footer-social-links-icon-container']['display']          = 'flex';
      $css_array['.footer-social-links-icon-container']['justify-content']  = 'center';
      $css_array['.footer-social-links-icon-container']['width']            = '1.5rem';
      $css_array['.footer-social-links-icon-container']['height']           = '1.5rem';

      $css_array['.footer-social-links-icon']['border']          = '0';
      $css_array['.footer-social-links-icon']['fill']            = 'var(--color-input-text)';
      $css_array['.footer-social-links-icon']['stroke']          = 'none';
      $css_array['.footer-social-links-icon']['stroke-linecap']  = 'round';
      $css_array['.footer-social-links-icon']['stroke-linejoin'] = 'round';
      $css_array['.footer-social-links-icon']['stroke-width']    = '0';
      $css_array['.footer-social-links-icon']['width']           = '1rem';

      $css_array['.footer-social-links a:active .footer-social-links-icon-container, .footer-social-links a:focus .footer-social-links-icon-container, .footer-social-links a:hover .footer-social-links-icon-container']['background-color'] = 'var(--color-input-background-hover)';

      $css_array['.footer-social-links a']['border-bottom'] = 'none';

      $css_array['.footer-social-links a:active, .footer-social-links a:focus, .footer-social-links a:hover']['border-bottom'] = 'none';

      $css_array['.footer-social-links a:active .footer-social-links-icon, .footer-social-links a:focus .footer-social-links-icon, .footer-social-links a:hover .footer-social-links-icon']['fill'] = 'var(--color-input-text-hover)';

      if ($origine_settings['global_css_transition'] === true) {
        $css_array['.footer-social-links-icon-container']['transition'] = 'all .2s ease-in-out';
        $css_array['.footer-social-links-icon']['transition'] = 'all .2s ease-in-out';
        $css_array['.footer-social-links a:active .footer-social-links-icon-container, .footer-social-links a:focus .footer-social-links-icon-container, .footer-social-links a:hover .footer-social-links-icon-container']['transition'] = 'all .2s ease-in-out';
        $css_array['.footer-social-links a:active .footer-social-links-icon, .footer-social-links a:focus .footer-social-links-icon, .footer-social-links a:hover .footer-social-links-icon']['transition'] = 'all .2s ease-in-out';
      }

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];

      $css_array['.footer-social-links-icon-container']['border'] = '1px solid var(--color-border)';

      $css       .= '@media (prefers-contrast: more), (prefers-contrast: less), (-ms-high-contrast: active), (-ms-high-contrast: black-on-white) {' . origineConfigArrayToCSS($css_array) . '}';
      $css_array  = [];
    }

    // Transitions
    if ($origine_settings['global_css_transition'] === true) {
      $css_array['a']['transition']                          = 'none';
      $css_array['a:active, a:focus, a:hover']['transition'] = 'none';

      $css_array['a .post-meta, a:hover .post-meta, a .post-excerpt, a:hover .post-excerpt']['transition'] = 'none';

      $css_array['input[type="submit"], .form-submit, .button']['transition'] = 'none';

      $css_array['input[type="submit"]:active, input[type="submit"]:focus, input[type="submit"]:hover, .button:active, .button:focus, .button:hover, .form-submit:active, .form-submit:focus, .form-submit:hover']['transition'] = 'none';

      if ($origine_settings['footer_social_links_diaspora']
        || $origine_settings['footer_social_links_discord']
        || $origine_settings['footer_social_links_facebook']
        || $origine_settings['footer_social_links_github']
        || $origine_settings['footer_social_links_mastodon']
        || $origine_settings['footer_social_links_signal']
        || $origine_settings['footer_social_links_tiktok']
        || $origine_settings['footer_social_links_twitter']
        || $origine_settings['footer_social_links_whatsapp']
      ) {
        $css_array['.footer-social-links-icon-container']['transition'] = 'none';

        $css_array['.footer-social-links-icon']['transition'] = 'none';

        $css_array['.footer-social-links a:hover .footer-social-links-icon-container']['transition'] = 'none';

        $css_array['.footer-social-links a:hover .footer-social-links-icon']['transition'] = 'none';
      }

      $css       .= '@media (prefers-reduced-motion:reduce) {' . origineConfigArrayToCSS($css_array) . '}';
      $css_array  = [];
    }

    $origine_settings['styles'] = htmlspecialchars($css, ENT_NOQUOTES);

    $core->blog->settings->origineConfig->put('origine_settings', $origine_settings);

    $core->blog->triggerBlog();

    // Clears template cache.
    if ($core->blog->settings->system->tpl_use_cache === true) {
      $core->emptyTemplatesCache();
    }

    dcPage::addSuccessNotice(__('Settings have been successfully updated.'));

    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}
?>
<html>
  <head>
  	<title><?php echo __('Origine Settings'); ?></title>
  </head>

  <body>
    <?php
    echo dcPage::breadcrumb(
      [
        html::escapeHTML($core->blog->name) => '',
        __('Origine Settings')              => '',
      ]
    );

    echo dcPage::notices();

    if ($core->blog->settings->system->theme === "Origine") :
      ?>
        <p>
          <?php
          printf(
            __('This plugin is only meant to customize Origine theme. To use it, please <a href="%s">install and activate Origine</a>.'),
            html::escapeURL($core->adminurl->get('admin.blog.theme'))
          );
          ?>
        </p>
      <?php else : ?>

      <?php
      $origine_settings_default = origineConfigSettings::default_settings();

      foreach($origine_settings_default as $setting => $value) {
        if (!array_key_exists($setting, $origine_settings)) {
          $origine_settings[$setting] = $value;
        }
      }
      ?>

      <form action="<?php echo $p_url; ?>" method="post">
        <p>
          <?php echo form::checkbox('activation', true, $origine_settings['activation']); ?>

          <label class="classic" for="activation"><?php echo __('Enable extension settings'); ?></label>
        </p>

        <p class="form-note">
          <?php echo __('If you do not check this box, your settings will be ignored.'); ?>
        </p>

        <h3><?php echo __('Global'); ?></h3>

        <div class="fieldset">
          <h4><?php echo __('Colors'); ?></h4>

          <p>
            <label for="global_color_scheme">
              <?php echo __('Color scheme'); ?>
            </label>

            <?php
            $combo_color_scheme = [
              __('System (default)') => 'system',
              __('Light')            => 'light',
              __('Dark')             => 'dark',
            ];

            echo form::combo('global_color_scheme', $combo_color_scheme, $origine_settings['global_color_scheme']);
            ?>
          </p>

          <p>
            <label for="link_color">
              <?php echo __('Link color'); ?>
            </label>

            <?php
            $combo_link_color = [
                __('Red (default)') => 'red',
                __('Blue')          => 'blue',
                __('Green')         => 'green',
                __('Orange')        => 'orange',
                __('Purple')        => 'purple',
            ];

            echo form::combo('global_color_link', $combo_link_color, $origine_settings['global_color_link']);
            ?>
          </p>

          <p>
            <?php echo form::checkbox('global_css_transition', true, $origine_settings['global_css_transition']); ?>

            <label class="classic" for="global_css_transition">
              <?php echo __('Add a color transition on link hover'); ?>
            </label>
          </p>

          <p class="form-note">
            <?php echo __('Accessibility: transitions are automatically disabled when the user has requested its system to minimize the amount of non-essential motion.'); ?>
          </p>
        </div>

        <div class="fieldset">
            <h4><?php echo __('Advanced settings'); ?></h4>

            <p>
              <?php echo form::checkbox('global_meta_generator', true, $origine_settings['global_meta_generator']); ?>

              <label class="classic" for="global_meta_generator"><?php echo __('Add <code>generator</code> meta tag'); ?></label>
            </p>

            <p class="form-note">
              <?php echo __("Allows you to add information to your pages without displaying it on your readers' screen."); ?>
            </p>
          </div>

        <h3><?php echo __('Header'); ?></h3>

        <div class="fieldset">
          <h4><?php echo __('Layout'); ?></h4>

          <p>
            <label for="header_align">
              <?php echo __('Header alignment'); ?>
            </label>

            <?php
            $combo_header_align = [
                __('Left (default)') => 'left',
                __('Center')         => 'center',
            ];

            echo form::combo('header_align', $combo_header_align, $origine_settings['header_align']);
            ?>
          </p>

          <p>
            <?php echo form::checkbox('header_widgets_nav', true, $origine_settings['header_widgets_nav']); ?>

            <label class="classic" for="header_widgets_nav"><?php echo __('Enable the navigation widget area'); ?></label>
          </p>

          <p class="form-note">
            <?php echo __('Check only if your have set widgets in the navigation area.'); ?>
          </p>
        </div>

        <div class="fieldset">
            <h4><?php echo __('Logo (beta)'); ?></h4>

            <p>
              <label for="header_logo_url">
                <?php echo __('The URL of your logo'); ?>
              </label>

              <?php echo form::field('header_logo_url', 30, 255, html::escapeHTML($origine_settings['header_logo_url'])); ?>
            </p>

            <p>
              <label for="header_logo_url_2x">
                <?php echo __('The URL of your logo for screens with doubled pixel density'); ?>
              </label>

              <?php echo form::field('header_logo_url_2x', 30, 255, html::escapeHTML($origine_settings['header_logo_url_2x'])); ?>
            </p>

            <p class="form-note">
              <?php echo __('To ensure a good display on screens with doubled pixel density (Retina), please provide an image that is twice the size the previous one.'); ?>
            </p>
          </div>

        <h3><?php echo __('Content'); ?></h3>

        <div class="fieldset">
          <h4><?php echo __('Post list'); ?></h4>

          <p>
            <label for="content_post_list_type"><?php echo __('Displaying of posts'); ?></label>

            <?php
            $combo_post_list_type = [
              __('Standard (default)') => 'standard',
              __('On one line')        => 'short',
              __('Full post')          => 'full',
            ];

            echo form::combo('content_post_list_type', $combo_post_list_type, $origine_settings['content_post_list_type']);
            ?>
          </p>

          <p>
            <?php echo form::checkbox('content_post_list_first_image', true, $origine_settings['content_post_list_first_image']); ?>

            <label class="classic" for="content_post_list_first_image">
              <?php echo __('Display the first image of the post (beta)'); ?>
            </label>
          </p>

          <p class="form-note">
            <?php echo __('Only if post displaying is set to "standard".'); ?>
          </p>
        </div>

        <div class="fieldset">
          <h4><?php echo __('Text formatting'); ?></h4>

          <p>
            <label for="content_font_family"><?php echo __('Font family'); ?></label>

            <?php
            $combo_font_family = [
              __('Serif (default)') => 'serif',
              __('Sans serif')      => 'sans-serif',
              __('Monospace')       => 'monospace',
            ];

            echo form::combo('content_font_family', $combo_font_family, $origine_settings['content_font_family']);
            ?>
          </p>

          <p class="form-note">
            <?php echo __('In any case, your theme will load system fonts of the device from which your site is viewed. This allows to reduce loading times and to have a graphic continuity with the system.'); ?>
          </p>

          <p>
            <label for="content_font_size">
              <?php echo __('Font size'); ?>
            </label>

            <?php
            $combo_font_size = [
                __('80%')                => 80,
                __('90%')                => 90,
                __('100% (recommended)') => 100,
                __('110%')               => 110,
                __('120%')               => 120,
            ];

            echo form::combo('content_font_size', $combo_font_size, $origine_settings['content_font_size']);
            ?>
          </p>

          <p class="form-note">
            <?php echo __('It is recommended not to change this setting. A size of 100% means that the texts on your site will be the size defined in the browser settings of each of your visitors.'); ?>
          </p>

          <p>
            <label for="content_text_align">
              <?php echo __('Text align'); ?>
            </label>

            <?php
            $combo_text_align = [
                __('Left (default)')                  => 'left',
                __('Justify')                         => 'justify',
                __('Justify except on small screens') => 'justify_not_mobile',
            ];

            echo form::combo('content_text_align', $combo_text_align, $origine_settings['content_text_align']);
            ?>
          </p>

          <p>
            <label for="content_hyphens">
              <?php echo __('Automatic hyphenation'); ?>
            </label>

            <?php
            $combo_content_hyphens = [
                __('Disable (default)')              => 'disabled',
                __('Enable')                         => 'enabled',
                __('Enable except on small screens') => 'enabled_not_mobile',
            ];

            echo form::combo('content_hyphens', $combo_content_hyphens, $origine_settings['content_hyphens']);
            ?>
          </p>
        </div>

        <div class="fieldset">
          <h4><?php echo __('Author'); ?></h4>

          <p>
            <label for="content_post_author_name">
              <?php echo __('Author name on posts'); ?>
            </label>

            <?php
            $combo_post_author_name = [
                __('Not displayed (default)')     => 'disabled',
                __('Next to the date')            => 'date',
                __('Below the post as signature') => 'signature',
            ];

            echo form::combo('content_post_author_name', $combo_post_author_name, $origine_settings['content_post_author_name']);
            ?>
          </p>

          <p>
            <?php echo form::checkbox('content_post_list_author_name', true, $origine_settings['content_post_list_author_name']); ?>

            <label class="classic" for="content_post_list_author_name">
              <?php echo __('Display the author name in the post list'); ?>
            </label>
          </p>
        </div>

        <!--<div class="fieldset">
          <h4><?php echo __('Share links'); ?></h4>

          <p>
            <?php echo __('Share links to display:'); ?>
          </p>

          <?php
          $social_links_supported = [
            'email'    => __('Email'),
            'facebook' => __('Facebook'),
            'print'    => __('Print'),
            'whatsapp' => __('WhatsApp'),
            'twitter'  => __('Twitter'),
          ];

          asort($social_links_supported);

          foreach($social_links_supported as $site_nicename => $site_name) {
            ?>
            <p>
              <?php echo form::checkbox('content_share_link_' . $site_nicename, true, $origine_settings['content_share_link_' . $site_nicename]); ?>

              <label class="classic" for="content_share_link_<?php echo $site_nicename; ?>">
                <?php echo $site_name; ?>
              </label>
            </p>
            <?php
          }
          ?>
        </div>-->

        <div class="fieldset">
            <h4><?php echo __('Comments'); ?></h4>

            <p>
              <?php echo form::checkbox('content_comment_links', true, $origine_settings['content_comment_links']); ?>

              <label class="classic" for="content_comment_links">
                <?php echo __('Add a link to the comment feed and trackbacks below the comment section'); ?>
              </label>
            </p>

            <p>
              <label for="content_post_email_author">
                <?php echo __('Allow visitors to send email to authors of posts and pages'); ?>
              </label>

              <?php
              $combo_post_email_author = [
                __('No (default)')                => 'disabled',
                __('Only when comments are open') => 'comments_open',
                __('Always')                      => 'always',
              ];

              echo form::combo('content_post_email_author', $combo_post_email_author, $origine_settings['content_post_email_author']);
              ?>
            </p>

            <p class="form-note warn">
              <?php printf(__('If this option is enabled, the email address of authors will be made public. If you prefer not to reveal email addresses, try the <a href="%s">Signal</a> plugin.'), 'https://plugins.dotaddict.org/dc2/details/signal'); ?>
            </p>
          </div>

        <h3><?php echo __('Widgets'); ?></h3>

        <div class="fieldset">
          <p>
            <?php echo form::checkbox('widgets_enabled', true, $origine_settings['widgets_enabled']); ?>

            <label class="classic" for="widgets_enabled"><?php echo __('Enable the <em>sidebar</em>'); ?></label>
          </p>

          <p class="form-note">
            <?php echo __("Origin is a one-column theme. It doesn't have a sidebar per se but you can insert content, in the same way, between your posts and the footer with widgets. If you don't have any widgets in the \"Extra sidebar\" section, you should uncheck this setting to remove unnecessary code from your pages."); ?>
          </p>
        </div>

        <h3><?php echo __('Footer'); ?></h3>

        <div class="fieldset">
            <p>
              <?php echo form::checkbox('footer_enabled', true, $origine_settings['footer_enabled']); ?>

              <label class="classic" for="footer_enabled"><?php echo __('Enable the footer'); ?></label>
            </p>

            <p class="form-note">
              <?php echo __('If your footer is empty or you want to remove everything at the bottom of your pages, uncheck this setting.'); ?>
            </p>

            <p>
              <label for="footer_align">
                <?php echo __('Footer alignment'); ?>
              </label>

              <?php
              $combo_footer_align = [
                  __('Left (default)') => 'left',
                  __('Center')         => 'center',
              ];

              echo form::combo('footer_align', $combo_footer_align, $origine_settings['footer_align']);
              ?>
            </p>

            <p>
              <?php echo form::checkbox('footer_credits', true, $origine_settings['footer_credits']); ?>

              <label class="classic" for="footer_credits">
                <?php echo __('Add a link to support Dotclear and Origine'); ?>
              </label>
            </p>

            <p>
              <label for="footer_social_links_diaspora">
                <?php echo __('Link to a Diaspora profile'); ?>
              </label>

              <?php echo form::field('footer_social_links_diaspora', 30, 255, html::escapeHTML($origine_settings['footer_social_links_diaspora'])); ?>
            </p>

            <p>
              <label for="footer_social_links_discord">
                <?php echo __('Link to a Discord server'); ?>
              </label>

              <?php echo form::field('footer_social_links_discord', 30, 255, html::escapeHTML($origine_settings['footer_social_links_discord'])); ?>
            </p>

            <p>
              <label for="footer_social_links_facebook">
                <?php echo __('Link to a Facebook profile or page'); ?>
              </label>

              <?php echo form::field('footer_social_links_facebook', 30, 255, html::escapeHTML($origine_settings['footer_social_links_facebook'])); ?>
            </p>

            <p>
              <label for="footer_social_links_github">
                <?php echo __('Link to a GitHub profile or page'); ?>
              </label>

              <?php echo form::field('footer_social_links_github', 30, 255, html::escapeHTML($origine_settings['footer_social_links_github'])); ?>
            </p>

            <p>
              <label for="footer_social_links_mastodon">
                <?php echo __('Link to a Mastodon profile'); ?>
              </label>

              <?php echo form::field('footer_social_links_mastodon', 30, 255, html::escapeHTML($origine_settings['footer_social_links_mastodon'])); ?>
            </p>

            <p>
              <label for="footer_social_links_signal">
                <?php echo __('A Signal number or a group link'); ?>
              </label>

              <?php echo form::field('footer_social_links_signal', 30, 255, html::escapeHTML($origine_settings['footer_social_links_signal'])); ?>
            </p>

            <p>
              <label for="footer_social_links_tiktok">
                <?php echo __('Link to a TikTok profile'); ?>
              </label>

              <?php echo form::field('footer_social_links_tiktok', 30, 255, html::escapeHTML($origine_settings['footer_social_links_tiktok'])); ?>
            </p>

            <p>
              <label for="footer_social_links_twitter">
                <?php echo __('A Twitter username'); ?>
              </label>

              <?php echo form::field('footer_social_links_twitter', 30, 255, html::escapeHTML($origine_settings['footer_social_links_twitter'])); ?>
            </p>

            <p>
              <label for="footer_social_links_whatsapp">
                <?php echo __('A WhatsApp number or a group link'); ?>
              </label>

              <?php echo form::field('footer_social_links_whatsapp', 30, 255, html::escapeHTML($origine_settings['footer_social_links_whatsapp'])); ?>
            </p>
          </div>

        <p>
          <?php echo $core->formNonce(); ?>

          <input type="submit" value="<?php echo __('Save'); ?>" />
        </p>
      </form>
    <?php endif; ?>
  </body>
</html>
