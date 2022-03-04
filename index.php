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

if (is_null($core->blog->settings->origineConfig->activation)) {
  try {
    // Activation
    $core->blog->settings->origineConfig->put('activation', false, 'boolean', 'Enable/disable the plugin settings', false, true);

    /**
     * Appearance
     */

    // Colors
    $core->blog->settings->origineConfig->put('color_scheme', 'system', 'string', 'Color scheme', false);
    $core->blog->settings->origineConfig->put('link_color', 'red', 'string', 'Link color', false);
    $core->blog->settings->origineConfig->put('css_transition', false, 'boolean', 'Color transition on link hover', false);

    // Layout
    $core->blog->settings->origineConfig->put('header_footer_align', 'left', 'string', 'Header & footer alignment', false);
    $core->blog->settings->origineConfig->put('post_list_type', 'standard', 'string', 'Post list appearance', false);
    $core->blog->settings->origineConfig->put('sidebar_enabled', true, 'boolean', 'Enable the sidebar', false);
    $core->blog->settings->origineConfig->put('footer_enabled', true, 'boolean', 'Enable the footer', false);

    // Logo
    $core->blog->settings->origineConfig->put('logo_url', '', 'string', 'URL of the logo', false);
    $core->blog->settings->origineConfig->put('logo_url_2x', '', 'string', 'URL of the logo (x2)', false);
    $core->blog->settings->origineConfig->put('logo_type', 'square', 'string', 'The type of logo', false);

    // Text Formatting
    $core->blog->settings->origineConfig->put('content_font_family', 'serif', 'string', 'Font family', false);
    $core->blog->settings->origineConfig->put('content_font_size', 100, 'integer', 'Font size', false);
    $core->blog->settings->origineConfig->put('content_text_align', 'left', 'string', 'Text align', false);
    $core->blog->settings->origineConfig->put('content_hyphens', '', 'string', 'Hyphenation', false);

    // Post Settings
    $core->blog->settings->origineConfig->put('post_author_name', 'disabled', 'string', 'Author name on posts', false);
    $core->blog->settings->origineConfig->put('post_list_author_name', false, 'boolean', 'Author name on posts in the post list', false);
    $core->blog->settings->origineConfig->put('post_list_comments', false, 'boolean', 'Link to comments in the post list', false);
    $core->blog->settings->origineConfig->put('comment_links', true, 'boolean', 'Link to the comment feed and trackbacks', false);
    $core->blog->settings->origineConfig->put('post_email_author', 'disabled', 'string', 'Option to email the author of a post', false);

    // Footer Settings
    $core->blog->settings->origineConfig->put('footer_credits', true, 'boolean', 'Dotclear and Origine credits', false);
    $core->blog->settings->origineConfig->put('social_links_diaspora', '', 'string', 'Link to Diaspora account', false);
    $core->blog->settings->origineConfig->put('social_links_discord', '', 'string', 'Link to Discord server', false);
    $core->blog->settings->origineConfig->put('social_links_facebook', '', 'string', 'Link to Facebook account', false);
    $core->blog->settings->origineConfig->put('social_links_github', '', 'string', 'Link to GitHub account', false);
    $core->blog->settings->origineConfig->put('social_links_mastodon', '', 'string', 'Link to Mastodon account', false);
    $core->blog->settings->origineConfig->put('social_links_signal', '', 'string', 'Link to a Signal number or group', false);
    $core->blog->settings->origineConfig->put('social_links_tiktok', '', 'string', 'Link to TikTok account', false);
    $core->blog->settings->origineConfig->put('social_links_twitter', '', 'string', 'Link to Twitter account', false);
    $core->blog->settings->origineConfig->put('social_links_whatsapp', '', 'string', 'Link to a WhatsApp number or group', false);

    // Advanced Settings
    $core->blog->settings->origineConfig->put('meta_generator', false, 'boolean', 'Meta generator', false);

    // All styles in one string
    $core->blog->settings->origineConfig->put('origine_styles', '', 'string', 'All custom styles in one string', false);

    $core->blog->triggerBlog();
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

// Activation
$activation = (bool) $core->blog->settings->origineConfig->activation;

/**
 * Appearance
 */

// Colors
$color_scheme   = (string) $core->blog->settings->origineConfig->color_scheme;
$link_color     = (string) $core->blog->settings->origineConfig->link_color;
$css_transition = (bool) $core->blog->settings->origineConfig->css_transition;

// Layout
$header_footer_align = (string) $core->blog->settings->origineConfig->header_footer_align;
$post_list_type      = (string) $core->blog->settings->origineConfig->post_list_type;
$sidebar_enabled     = (bool) $core->blog->settings->origineConfig->sidebar_enabled;
$footer_enabled      = (bool) $core->blog->settings->origineConfig->footer_enabled;

// Logo
$logo_url    = (string) $core->blog->settings->origineConfig->logo_url;
$logo_url_2x = (string) $core->blog->settings->origineConfig->logo_url_2x;
$logo_type   = (string) $core->blog->settings->origineConfig->logo_type;

// Text Formatting
$content_font_family = (string) $core->blog->settings->origineConfig->content_font_family;
$content_font_size   = (int) $core->blog->settings->origineConfig->content_font_size;
$content_text_align  = (string) $core->blog->settings->origineConfig->content_text_align;
$content_hyphens     = (string) $core->blog->settings->origineConfig->content_hyphens;

// Post Settings
$post_author_name      = (string) $core->blog->settings->origineConfig->post_author_name;
$post_list_author_name = (bool) $core->blog->settings->origineConfig->post_list_author_name;
$post_list_comments    = (bool) $core->blog->settings->origineConfig->post_list_comments;
$comment_links         = (bool) $core->blog->settings->origineConfig->comment_links;
$post_email_author     = (string) $core->blog->settings->origineConfig->post_email_author;

// Footer Settings
$footer_credits        = (bool) $core->blog->settings->origineConfig->footer_credits;
$social_links_diaspora = (string) $core->blog->settings->origineConfig->social_links_diaspora;
$social_links_discord  = (string) $core->blog->settings->origineConfig->social_links_discord;
$social_links_facebook = (string) $core->blog->settings->origineConfig->social_links_facebook;
$social_links_github   = (string) $core->blog->settings->origineConfig->social_links_github;
$social_links_mastodon = (string) $core->blog->settings->origineConfig->social_links_mastodon;
$social_links_signal   = (string) $core->blog->settings->origineConfig->social_links_signal;
$social_links_tiktok   = (string) $core->blog->settings->origineConfig->social_links_tiktok;
$social_links_twitter  = (string) $core->blog->settings->origineConfig->social_links_twitter;
$social_links_whatsapp = (string) $core->blog->settings->origineConfig->social_links_whatsapp;

// Advanced Settings
$meta_generator = (bool) $core->blog->settings->origineConfig->meta_generator;

// All styles
$origine_styles = (string) $core->blog->settings->origineConfig->origine_styles;

if (!empty($_POST)) {
  try {

    /**
     * Get settings from the form
     * and escape them.
     */

    // Activation
    $activation = !empty($_POST['activation']);

    /**
     * Appearance
     */

    // Colors
    $color_scheme   = trim(html::escapeHTML($_POST['color_scheme']));
    $link_color     = trim(html::escapeHTML($_POST['link_color']));
    $css_transition = !empty($_POST['css_transition']);

    // Layout
    $header_footer_align = trim(html::escapeHTML($_POST['header_footer_align']));
    $post_list_type      = trim(html::escapeHTML($_POST['post_list_type']));
    $sidebar_enabled     = !empty($_POST['sidebar_enabled']);
    $footer_enabled      = !empty($_POST['footer_enabled']);

    // Logo
    $logo_url    = trim(html::escapeHTML($_POST['logo_url']));
    $logo_url_2x = trim(html::escapeHTML($_POST['logo_url_2x']));
    $logo_type   = trim(html::escapeHTML($_POST['logo_type']));

    // Text formatting
    $content_font_family = trim(html::escapeHTML($_POST['content_font_family']));
    $content_font_size   = abs((int) $_POST['content_font_size']);
    $content_text_align  = trim(html::escapeHTML($_POST['content_text_align']));
    $content_hyphens     = trim(html::escapeHTML($_POST['content_hyphens']));

    // Post Settings
    $post_author_name      = trim(html::escapeHTML($_POST['post_author_name']));
    $post_list_author_name = !empty($_POST['post_list_author_name']);
    $post_list_comments    = !empty($_POST['post_list_comments']);
    $comment_links         = !empty($_POST['comment_links']);
    $post_email_author     = trim(html::escapeHTML($_POST['post_email_author']));

    // Footer Settings
    $footer_credits        = !empty($_POST['footer_credits']);
    $social_links_diaspora = trim(html::escapeHTML($_POST['social_links_diaspora']));
    $social_links_discord  = trim(html::escapeHTML($_POST['social_links_discord']));
    $social_links_facebook = trim(html::escapeHTML($_POST['social_links_facebook']));
    $social_links_github   = trim(html::escapeHTML($_POST['social_links_github']));
    $social_links_mastodon = trim(html::escapeHTML($_POST['social_links_mastodon']));
    $social_links_signal   = trim(html::escapeHTML($_POST['social_links_signal']));
    $social_links_tiktok   = trim(html::escapeHTML($_POST['social_links_tiktok']));
    $social_links_twitter  = trim(html::escapeHTML($_POST['social_links_twitter']));
    $social_links_whatsapp = trim(html::escapeHTML($_POST['social_links_whatsapp']));

    // Advanced Settings
    $meta_generator = !empty($_POST['meta_generator']);

    /**
     * Save settings in the database.
     */
    $core->blog->settings->addNamespace('origineConfig');

    // Activation
    $core->blog->settings->origineConfig->put('activation', $activation);

    /**
     * Appearance
     */

    // Colors
    $core->blog->settings->origineConfig->put('color_scheme', $color_scheme);
    $core->blog->settings->origineConfig->put('link_color', $link_color);
    $core->blog->settings->origineConfig->put('css_transition', $css_transition);

    // Layout
    $core->blog->settings->origineConfig->put('header_footer_align', $header_footer_align);
    $core->blog->settings->origineConfig->put('post_list_type', $post_list_type);
    $core->blog->settings->origineConfig->put('sidebar_enabled', $sidebar_enabled);
    $core->blog->settings->origineConfig->put('footer_enabled', $footer_enabled);

    // Logo
    $core->blog->settings->origineConfig->put('logo_url', $logo_url);
    $core->blog->settings->origineConfig->put('logo_url_2x', $logo_url_2x);
    $core->blog->settings->origineConfig->put('logo_type', $logo_type);

    // Text Formatting
    $core->blog->settings->origineConfig->put('content_font_family', $content_font_family);
    $core->blog->settings->origineConfig->put('content_font_size', $content_font_size);
    $core->blog->settings->origineConfig->put('content_text_align', $content_text_align);
    $core->blog->settings->origineConfig->put('content_hyphens', $content_hyphens);

    // Post Settings
    $core->blog->settings->origineConfig->put('post_author_name', $post_author_name);
    $core->blog->settings->origineConfig->put('post_list_author_name', $post_list_author_name);
    $core->blog->settings->origineConfig->put('post_list_comments', $post_list_comments);
    $core->blog->settings->origineConfig->put('comment_links', $comment_links);
    $core->blog->settings->origineConfig->put('post_email_author', $post_email_author);

    // Footer settings
    $core->blog->settings->origineConfig->put('footer_credits', $footer_credits);
    $core->blog->settings->origineConfig->put('social_links_diaspora', $social_links_diaspora);
    $core->blog->settings->origineConfig->put('social_links_discord', $social_links_discord);
    $core->blog->settings->origineConfig->put('social_links_facebook', $social_links_facebook);
    $core->blog->settings->origineConfig->put('social_links_github', $social_links_github);
    $core->blog->settings->origineConfig->put('social_links_mastodon', $social_links_mastodon);
    $core->blog->settings->origineConfig->put('social_links_signal', $social_links_signal);
    $core->blog->settings->origineConfig->put('social_links_tiktok', $social_links_tiktok);
    $core->blog->settings->origineConfig->put('social_links_twitter', $social_links_twitter);
    $core->blog->settings->origineConfig->put('social_links_whatsapp', $social_links_whatsapp);

    // Advanced Settings
    $core->blog->settings->origineConfig->put('meta_generator', $meta_generator);

    /**
     * And save styles too!
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

    $the_color = array_key_exists($link_color, $link_colors) ? $link_color : 'red';

    /**
     * Put all styles in a array ($css_array)
     * to save then in the database as a string ($css)
     * via the function origineConfigArrayToCSS().
     */
    $css       = '';
    $css_array = [];

    if ($color_scheme === 'system') {
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
    } elseif ($color_scheme === 'dark') {
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
    if ($css_transition === true) {
      $css_array['a']['transition']                          = 'all .2s ease-in-out';
      $css_array['a:active, a:focus, a:hover']['transition'] = 'all .2s ease-in-out';

      $css_array['a .post-meta, a .post-excerpt']['transition']             = 'all .2s ease-in-out';
      $css_array['a:hover .post-meta, a:hover .post-excerpt']['transition'] = 'all .2s ease-in-out';

      $css_array['input[type="submit"], .form-submit, .button']['transition'] = 'all .2s ease-in-out';

      $css_array['input[type="submit"]:active, input[type="submit"]:focus, input[type="submit"]:hover, .button:active, .button:focus, .button:hover, .form-submit:active, .form-submit:focus, .form-submit:hover']['transition'] = 'all .2s ease-in-out';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Header & footer alignment
    if ($header_footer_align === 'center') {
      $css_array['#site-header']['text-align'] = 'center';
      $css_array['#site-footer']['text-align'] = 'center';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Logo
    if ($logo_url) {
      $css_array['.site-logo-container']['margin-bottom'] = '1em';

      $css_array['.site-logo-link']['border-bottom'] = 'none';

      $css_array['.site-logo-link:active, .site-logo-link:focus, .site-logo-link:hover']['border-bottom'] = 'none';

      $css_array['.site-logo']['display'] = 'block';

      if ($logo_type === 'square') {
        $css_array['.site-logo-container']['display'] = 'inline-block';

        $css_array['.site-logo-link']['display'] = 'inline-block';

        $css_array['.site-logo']['max-height'] = '150px';
        $css_array['.site-logo']['max-width']  = '150px';
      } elseif ($logo_type === 'round') {
        $css_array['.site-logo-container']['display'] = 'inline-block';

        $css_array['.site-logo-link']['border-radius'] = '50%';
        $css_array['.site-logo-link']['display']       = 'inline-block';

        $css_array['.site-logo']['border-radius'] = '50%';
        $css_array['.site-logo']['max-height']    = '150px';
        $css_array['.site-logo']['max-width']     = '150px';
      } else {
        $css_array['.site-logo-container']['display'] = 'block';

        $css_array['.site-logo-link']['display'] = 'block';

        $css_array['.site-logo']['width'] = '100%';
      }

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Font family
    if ($content_font_family === 'serif') {
      $css_array['body']['font-family'] = '"Iowan Old Style", "Apple Garamond", Baskerville, "Times New Roman", "Droid Serif", Times, "Source Serif Pro", serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';
    } elseif ($content_font_family === 'sans-serif') {
      $css_array['body']['font-family'] = '-apple-system, BlinkMacSystemFont, "Avenir Next", Avenir, "Segoe UI", "Helvetica Neue", Helvetica, Ubuntu, Roboto, Noto, Arial, sans-serif';
    } else {
      $css_array['body']['font-family'] = 'Menlo, Consolas, Monaco, "Liberation Mono", "Lucida Console", monospace';
    }

    // Font size
    if ($content_font_size) {
      $css_array['body']['font-size'] = abs((int) $content_font_size / 100) . 'em';
    }

    // Post list appearance
    switch ($post_list_type) {
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

        $css_array['.post-list-standard .post-list-comment']['display']     = 'inline-block';
        $css_array['.post-list-standard .post-list-comment']['margin-left'] = '.25em';

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

    if ($content_text_align === 'justify') {
      $css_array[$content_text]['text-align'] = 'justify';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    } elseif ($content_text_align === 'justify_not_mobile') {
      $css_array[$content_text]['text-align'] = 'justify';

      $css       .= '@media only screen and (min-width: calc(1rem * 20 + (2em * 2) + 1px)) {' . origineConfigArrayToCSS($css_array) . '}';
      $css_array  = [];
    }

    // Hyphens
    if ($content_hyphens !== 'disabled' ) {
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

      if ($content_hyphens !== 'enabled_not_mobile') {
        $css       .= origineConfigArrayToCSS($css_array);
        $css_array  = [];
      } else {
        $css       .= '@media only screen and (min-width: calc(1rem * 20 + (2em * 2) + 1px)) {' . origineConfigArrayToCSS($css_array) . '}';
        $css_array  = [];
      }
    }

    // Social links
    if ($social_links_diaspora
      || $social_links_discord
      || $social_links_facebook
      || $social_links_github
      || $social_links_mastodon
      || $social_links_signal
      || $social_links_tiktok
      || $social_links_twitter
      || $social_links_whatsapp
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

      if ($css_transition === true) {
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
    if ($css_transition === true) {
      $css_array['a']['transition']                          = 'none';
      $css_array['a:active, a:focus, a:hover']['transition'] = 'none';

      $css_array['a .post-meta, a:hover .post-meta, a .post-excerpt, a:hover .post-excerpt']['transition'] = 'none';

      $css_array['input[type="submit"], .form-submit, .button']['transition'] = 'none';

      $css_array['input[type="submit"]:active, input[type="submit"]:focus, input[type="submit"]:hover, .button:active, .button:focus, .button:hover, .form-submit:active, .form-submit:focus, .form-submit:hover']['transition'] = 'none';

      if ($social_links_diaspora
        || $social_links_discord
        || $social_links_facebook
        || $social_links_github
        || $social_links_mastodon
        || $social_links_signal
        || $social_links_tiktok
        || $social_links_twitter
        || $social_links_whatsapp
      ) {
        $css_array['.footer-social-links-icon-container']['transition'] = 'none';

        $css_array['.footer-social-links-icon']['transition'] = 'none';

        $css_array['.footer-social-links a:hover .footer-social-links-icon-container']['transition'] = 'none';

        $css_array['.footer-social-links a:hover .footer-social-links-icon']['transition'] = 'none';
      }

      $css       .= '@media (prefers-reduced-motion:reduce) {' . origineConfigArrayToCSS($css_array) . '}';
      $css_array  = [];
    }

    $core->blog->settings->origineConfig->put('origine_styles', htmlspecialchars($css, ENT_NOQUOTES));

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

    $themes_customizable = ['origine'];

    if (!in_array($core->blog->settings->system->theme, $themes_customizable, true)) :
      echo '<p>' . sprintf(
        __('This plugin is only meant to customize Origine theme. To use it, please <a href="%s">install and/or activate Origine</a>.'), html::escapeURL($core->adminurl->get('admin.blog.theme'))) . '</p>';
    else :
      ?>

      <form action="<?php echo $p_url; ?>" method="post">
        <p>
          <?php echo form::checkbox('activation', 1, $activation); ?>

          <label for="activation" class="classic"><?php echo __('Enable extension settings'); ?></label>
        </p>

        <p class="form-note">
          <?php echo __('If you do not check this box, the settings below will be ignored.'); ?>
        </p>

        <h3><?php echo __('Design'); ?></h3>

        <div class="fieldset">
          <h4><?php echo __('Colors'); ?></h4>

          <p class="field wide">
            <label for="color_scheme" class="classic">
              <?php echo __('Color scheme'); ?>
            </label>

            <?php
            $combo_color_scheme = [
              __('System (default)') => 'system',
              __('Light')            => 'light',
              __('Dark')             => 'dark',
            ];

            echo form::combo('color_scheme', $combo_color_scheme, $color_scheme);
            ?>
          </p>

          <p class="field wide">
            <label for="link_color" class="classic">
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

            echo form::combo('link_color', $combo_link_color, $link_color);
            ?>
          </p>

          <p class="field wide">
            <label for="css_transition" class="classic">
              <?php echo __('Add a color transition on link hover'); ?>
            </label>

            <?php echo form::checkbox('css_transition', 1, $css_transition); ?>
          </p>

          <p class="form-note">
            <?php echo __('Accessibility: transitions are automatically disabled when the user has requested its system to minimize the amount of non-essential motion.'); ?>
          </p>

          <h4><?php echo __('Layout'); ?></h4>

          <p class="field wide">
            <label for="" class="classic">
              <?php echo __('Header and footer alignment'); ?>
            </label>

            <?php
            $combo_header_footer_align = [
                __('Left (default)') => 'left',
                __('Center')         => 'center',
            ];

            echo form::combo('header_footer_align', $combo_header_footer_align, $header_footer_align);
            ?>
          </p>

          <p class="field wide">
            <label for="post_list_type" class="classic"><?php echo __('Displaying of posts in the post list'); ?></label>

            <?php
            $combo_post_list_type = [
              __('Standard (default)') => 'standard',
              __('On one line')        => 'short',
              __('Full post')          => 'full',
            ];

            echo form::combo('post_list_type', $combo_post_list_type, $post_list_type);
            ?>
          </p>

          <p class="field wide">
            <label for="sidebar_enabled" class="classic"><?php echo __('Enable the <em>sidebar</em>'); ?></label>

            <?php echo form::checkbox('sidebar_enabled', 1, $sidebar_enabled); ?>
          </p>

          <p class="form-note">
            <?php echo __("Origin is a one-column theme. It doesn't have a sidebar per se but you can insert content, in the same way, between your posts and the footer with widgets. If you don't have any widgets in the \"Navigation sidebar\" or \"Extra sidebar\" sections, you should uncheck this setting to remove unnecessary code from your pages."); ?>
          </p>

          <p class="field wide">
            <label for="footer_enabled" class="classic"><?php echo __('Enable the footer'); ?></label>

            <?php echo form::checkbox('footer_enabled', 1, $footer_enabled); ?>
          </p>

          <p class="form-note">
            <?php echo __('If your footer is empty or you want to remove everything at the bottom of your pages, uncheck this setting.'); ?>
          </p>

          <h4><?php echo __('Logo'); ?></h4>

          <p class="field wide">
            <label for="logo_type" class="classic">
              <?php echo __('Logo type'); ?>
            </label>

            <?php
            $combo_logo_type = [
              __('Square (default)') => 'square',
              __('Round')            => 'round',
              __('Banner')           => 'banner',
            ];

            echo form::combo('logo_type', $combo_logo_type, $logo_type);
            ?>
          </p>

          <p class="field wide">
            <label for="logo_url" class="classic">
              <?php echo __('URL of your logo'); ?>
            </label>

            <?php echo form::field('logo_url', 30, 255, html::escapeHTML($logo_url)); ?>
          </p>

          <p class="form-note">
            <?php echo __('Recommanded size: 150×150px (square and round) or 480px wide (banner).'); ?>
          </p>

          <p class="field wide">
            <label for="logo_url_2x" class="classic">
              <?php echo __('URL of your logo for screens with doubled pixel density'); ?>
            </label>

            <?php echo form::field('logo_url_2x', 30, 255, html::escapeHTML($logo_url_2x)); ?>
          </p>

          <p class="form-note">
            <?php echo __('To ensure a good display on screens with doubled pixel density (Retina), please provide an image that is twice the size the previous one (300×300px or 960px wide).'); ?>
          </p>

          <h4><?php echo __('Text formatting'); ?></h4>

          <p class="field wide">
            <label for="content_font_family" class="classic"><?php echo __('Font family'); ?></label>

            <?php
            $combo_font_family = [
              __('Serif (default)') => 'serif',
              __('Sans serif')      => 'sans-serif',
              __('Monospace')       => 'monospace',
            ];

            echo form::combo('content_font_family', $combo_font_family, $content_font_family);
            ?>
          </p>

          <p class="form-note">
            <?php echo __('In any case, your theme will load system fonts of the device from which your site is viewed. This allows to reduce loading times and to have a graphic continuity with the system.'); ?>
          </p>

          <p class="field wide">
            <label for="content_font_size" class="classic">
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

            echo form::combo('content_font_size', $combo_font_size, $content_font_size);
            ?>
          </p>

          <p class="form-note">
            <?php echo __('It is recommended not to change this setting. A size of 100% means that the texts on your site will be the size defined in the browser settings of each of your visitors.'); ?>
          </p>

          <p class="field wide">
            <label for="content_text_align" class="classic">
              <?php echo __('Text align'); ?>
            </label>

            <?php
            $combo_text_align = [
                __('Left (default)')                  => 'left',
                __('Justify')                         => 'justify',
                __('Justify except on small screens') => 'justify_not_mobile',
            ];

            echo form::combo('content_text_align', $combo_text_align, $content_text_align);
            ?>
          </p>

          <p class="field wide">
            <label for="content_hyphens" class="classic">
              <?php echo __('Automatic hyphenation'); ?>
            </label>

            <?php
            $combo_content_hyphens = [
                __('Disable (default)')              => 'disabled',
                __('Enable')                         => 'enabled',
                __('Enable except on small screens') => 'enabled_not_mobile',
            ];

            echo form::combo('content_hyphens', $combo_content_hyphens, $content_hyphens);
            ?>
          </p>
        </div>

        <h3><?php echo __('Post settings'); ?></h3>

        <div class="fieldset">
          <h4><?php echo __('Author'); ?></h4>

          <p class="field wide">
            <label for="" class="classic">
              <?php echo __('Author name on posts'); ?>
            </label>

            <?php
            $combo_post_author_name = [
                __('Not displayed (default)')     => 'disabled',
                __('Next to the date')            => 'date',
                __('Below the post as signature') => 'signature',
            ];

            echo form::combo('post_author_name', $combo_post_author_name, $post_author_name);
            ?>
          </p>

          <p class="field wide">
            <label for="" class="classic">
              <?php echo __('Display the author name in the post list'); ?>
            </label>

            <?php echo form::checkbox('post_list_author_name', 1, $post_list_author_name); ?>
          </p>

          <h4><?php echo __('Comments'); ?></h4>

          <p class="field wide">
            <label for="post_list_comments" class="classic">
              <?php echo __('Display the number of comments in the post list (only if the post has comments)'); ?>
            </label>

            <?php echo form::checkbox('post_list_comments', 1, $post_list_comments); ?>
          </p>

          <p class="field wide">
            <label for="comment_links" class="classic">
              <?php echo __('Add a link to the comment feed and trackbacks below the comment section'); ?>
            </label>

            <?php echo form::checkbox('comment_links', 1, $comment_links); ?>
          </p>

          <p class="field wide">
            <label for="post_email_author" class="classic">
              <?php echo __('Allow visitors to send email to authors of posts and pages'); ?>
            </label>

            <?php
            $combo_post_email_author = [
              __('No (default)')                => 'disabled',
              __('Only when comments are open') => 'comments_open',
              __('Always')                      => 'always',
            ];

            echo form::combo('post_email_author', $combo_post_email_author, $post_email_author);
            ?>
          </p>

          <p class="form-note warn">
            <?php printf(__('If this option is enabled, the email address of authors will be made public. If you prefer not to reveal email addresses, try the <a href="%s">Signal</a> plugin.'), 'https://plugins.dotaddict.org/dc2/details/signal'); ?>
          </p>
        </div>

        <h3><?php echo __('Footer settings'); ?></h3>

        <div class="fieldset">
          <p class="field wide">
            <label for="footer_credits" class="classic">
              <?php echo __('Add a link to support Dotclear and Origine'); ?>
            </label>

            <?php echo form::checkbox('footer_credits', 1, $footer_credits); ?>
          </p>

          <p class="field wide">
            <label for="social_links_diaspora" class="classic">
              <?php echo __('Link to your Diaspora* profile'); ?>
            </label>

            <?php echo form::field('social_links_diaspora', 30, 255, html::escapeHTML($social_links_diaspora)); ?>
          </p>

          <p class="field wide">
            <label for="social_links_discord" class="classic">
              <?php echo __('Link to your Discord server'); ?>
            </label>

            <?php echo form::field('social_links_discord', 30, 255, html::escapeHTML($social_links_discord)); ?>
          </p>

          <p class="field wide">
            <label for="social_links_facebook" class="classic">
              <?php echo __('Link to your Facebook profile or page'); ?>
            </label>

            <?php echo form::field('social_links_facebook', 30, 255, html::escapeHTML($social_links_facebook)); ?>
          </p>

          <p class="field wide">
            <label for="social_links_github" class="classic">
              <?php echo __('Link to a GitHub page'); ?>
            </label>

            <?php echo form::field('social_links_github', 30, 255, html::escapeHTML($social_links_github)); ?>
          </p>

          <p class="field wide">
            <label for="social_links_mastodon" class="classic">
              <?php echo __('Link to your Mastodon profile'); ?>
            </label>

            <?php echo form::field('social_links_mastodon', 30, 255, html::escapeHTML($social_links_mastodon)); ?>
          </p>

          <p class="field wide">
            <label for="social_links_signal" class="classic">
              <?php echo __('Your Signal number or a group link'); ?>
            </label>

            <?php echo form::field('social_links_signal', 30, 255, html::escapeHTML($social_links_signal)); ?>
          </p>

          <p class="field wide">
            <label for="social_links_tiktok" class="classic">
              <?php echo __('Link to your TikTok profile'); ?>
            </label>

            <?php echo form::field('social_links_tiktok', 30, 255, html::escapeHTML($social_links_tiktok)); ?>
          </p>

          <p class="field wide">
            <label for="social_links_twitter" class="classic">
              <?php echo __('Your Twitter username'); ?>
            </label>

            <?php echo form::field('social_links_twitter', 30, 255, html::escapeHTML($social_links_twitter)); ?>
          </p>

          <p class="field wide">
            <label for="social_links_whatsapp" class="classic">
              <?php echo __('Your WhatsApp number or a group link'); ?>
            </label>

            <?php echo form::field('social_links_whatsapp', 30, 255, html::escapeHTML($social_links_whatsapp)); ?>
          </p>
        </div>

        <h3><?php echo __('Advanced settings'); ?></h3>

        <div class="fieldset">
          <p class="form-note">
            <?php echo __("Allows you to add information to your pages without displaying it on your readers' screen."); ?>
          </p>

          <p class="field wide">
            <label for="meta_generator" class="classic">
              <?php echo __('Add <code>generator</code> meta tag'); ?>
            </label>

            <?php echo form::checkbox('meta_generator', 1, $meta_generator); ?>
          </p>
        </div>

        <p>
          <?php echo $core->formNonce(); ?>

          <input type="submit" value="<?php echo __('Save'); ?>" />
        </p>

        <p class="form-note warn">
          <?php
          if ($core->plugins->moduleExists('maintenance') === true) {
            printf(
              __('If the changes are not effective after saving, consider emptying the templates cache directory with the <a href="%s">Maintenance</a> plugin (Servicing › Purge).'),
              html::escapeURL($core->adminurl->get('admin.plugin', ['p' => 'maintenance']))
            );
          } else {
            printf(
              __('If the changes are not effective after saving, consider emptying the templates cache directory using the Maintenance plugin, which you can activate from the <a href="%s">Plugins management</a> page.'),
              html::escapeURL($core->adminurl->get('admin.plugins') . '#plugin-deactivate')
            );
          }
          ?>
        </p>
      </form>
    <?php endif; ?>
  </body>
</html>
