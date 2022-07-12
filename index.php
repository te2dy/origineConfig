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
  $output = '';

  if (
    $setting_id !== ''
    && !empty($settings) && !empty($default_settings)
    && array_key_exists($setting_id, $default_settings) === true
  ) {
    $output .= '<p>';

    if ($default_settings[$setting_id]['type'] === 'checkbox') {
      $output .= form::checkbox(
        $setting_id,
        true,
        $settings[$setting_id]
      );
      $output .= '<label class="classic" for="' . $setting_id . '">';
      $output .= $default_settings[$setting_id]['title'];
      $output .= '</label>';
    } elseif ($default_settings[$setting_id]['type'] === 'select' || $default_settings[$setting_id]['type'] === 'select_int') {
      $output .= '<label for="' . $setting_id . '">';
      $output .= $default_settings[$setting_id]['title'];
      $output .= '</label>';
      $output .= form::combo(
        $setting_id,
        $default_settings[$setting_id]['choices'],
        strval($settings[$setting_id])
      );
    } elseif ($default_settings[$setting_id]['type'] === 'text') {
      $output .= '<label for="' . $setting_id . '">';
      $output .= $default_settings[$setting_id]['title'];
      $output .= '</label>';
      $output .= form::field(
        $setting_id,
        30,
        255,
        $settings[$setting_id]
      );
    }

    $output .= '</p>';

    // If the setting has a description, displays it as a note.
    if ($default_settings[$setting_id]['description']) {
      $output .= '<p class="form-note">';
      $output .= $default_settings[$setting_id]['description'];

      if ($default_settings[$setting_id]['type'] === 'checkbox') {
        if ($default_settings[$setting_id]['default'] === true) {
          $output .= ' ' . __('Default: checked.');
        } else {
          $output .= ' ' . __('Default: unchecked.');
        }
      }

      $output .= '</p>';
    }
  }

  $output .= '</p>';

  return $output;
}

function option_supported($theme_current, $theme_supported = '')
{
  if (
    isset($theme_supported)
    && (
      (
        is_string($theme_supported) === true
        && ($theme_supported === $theme_current || $theme_supported === 'all')
      )
      ||
      (
        is_array($theme_supported) === true
        && in_array($theme_current, $theme_supported, true) === true
      )
    )
  ) {
    return true;
  } else {
    return false;
  }
}

$theme = $core->blog->settings->system->theme;

$default_settings = origineConfigSettings::default_settings();

$core->blog->settings->addNamespace('origineConfig');

// Adds all default settings values if necessary.
foreach($default_settings as $setting_id => $setting_data) {
  if (!$core->blog->settings->origineConfig->$setting_id) {
    if ($setting_data['type'] === 'checkbox') {
      $setting_type = 'boolean';
    } elseif ($setting_data['type'] === 'select_int') {
      $setting_type = 'integer';
    } else {
      $setting_type = 'string';
    }

    $core->blog->settings->origineConfig->put(
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
    $settings[$setting_id] = (boolean) $core->blog->settings->origineConfig->$setting_id;
  } elseif ($setting_data['type'] === 'select_int') {
    $settings[$setting_id] = (integer) $core->blog->settings->origineConfig->$setting_id;
  } else {
    $settings[$setting_id] = $core->blog->settings->origineConfig->$setting_id;
  }
}

if (!empty($_POST)) {
  try {
    // Saves options.
    foreach ($settings as $id => $value) {
      if (option_supported($theme, $default_settings[$id]['theme']) === true && $id !== 'global_css') {
        if ($default_settings[$id]['type'] === 'checkbox') {
          if (!empty($_POST[$id]) && intval($_POST[$id]) === 1) {
            $core->blog->settings->origineConfig->put($id, true);
          } else {
            $core->blog->settings->origineConfig->put($id, false);
          }
        } else {
          $core->blog->settings->origineConfig->put($id, trim(html::escapeHTML($_POST[$id])));
        }
      }
    }

    /**
     * Saves custom styles.
     * 
     * Put all styles in a array ($css_array)
     * to save then in the database as a string ($css) with put()
     * formatted via the function origineConfigArrayToCSS().
     */
    $css       = '';
    $css_array = [];

    // Font family.
    if (isset($_POST['global_font_family']) === true && $_POST['global_font_family'] === 'serif') {
      $css_array[':root']['--font-family'] = '"Iowan Old Style", "Apple Garamond", Baskerville, "Times New Roman", "Droid Serif", Times, "Source Serif Pro", serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol"';
    } elseif (isset($_POST['global_font_family']) === true && $_POST['global_font_family'] === 'sans-serif') {
      $css_array[':root']['--font-family'] = '-apple-system, BlinkMacSystemFont, "Avenir Next", Avenir, "Segoe UI", "Helvetica Neue", Helvetica, Ubuntu, Roboto, Noto, Arial, sans-serif';
    } else {
      $css_array[':root']['--font-family'] = 'Menlo, Consolas, Monaco, "Liberation Mono", "Lucida Console", monospace';
    }

    // Font size.
    if (isset($_POST['global_font_size']) === true && intval($_POST['global_font_size']) > 0) {
      $css_array[':root']['--font-size'] = ($_POST['global_font_size'] / 100) . 'em';
    }

    // Secondary color hue.
    $secondary_colors_allowed = [
      'red'    => '0',
      'blue'   => '220',
      'green'  => '120',
      'orange' => '40',
      'purple' => '290',
    ];

    if (array_key_exists($_POST['global_color_secondary'], $secondary_colors_allowed) === true) {
      $css_array[':root']['--color-h'] = $secondary_colors_allowed[$_POST['global_color_secondary']];
    } else {
      $css_array[':root']['--color-h'] = $secondary_colors_allowed[$default_settings['global_color_secondary']['default']];
    }

    // Page width.
    $page_width_allowed = [30, 35, 40];

    if (in_array(intval($_POST['global_page_width']), $page_width_allowed, true) === true) {
      $css_array[':root']['--page-width'] = intval($_POST['global_page_width']) . 'em';
    } else {
      $css_array[':root']['--page-width'] = '30em';
    }

    // Sets the order of site elements.
    $structure_order = [2 => '',];

    if (isset($_POST['widgets_nav_position']) === true && $_POST['widgets_nav_position'] === 'header_content') {
      $structure_order[2] = '--order-widgets-nav';
    }

    if ($structure_order[2] === '') {
      $structure_order[2] = '--order-content';
    } else {
      $structure_order[] = '--order-content';
    }

    if (isset($_POST['widgets_nav_position']) === true && $_POST['widgets_nav_position'] === 'content_footer') {
      $structure_order[] = '--order-widgets-nav';
    }

    if (isset($_POST['widgets_extra_enabled']) === true && $_POST['widgets_extra_enabled'] === '1') {
      $structure_order[] = '--order-widgets-extra';
    }

    if (isset($_POST['footer_enabled']) === true && $_POST['footer_enabled'] === '1') {
      $structure_order[] = '--order-footer';
    }

    $css_array[':root']['--order-content'] = array_search('--order-content', $structure_order);

    if (in_array('--order-widgets-nav', $structure_order, true) === true) {
      $css_array[':root']['--order-widgets-nav'] = array_search('--order-widgets-nav', $structure_order);
    }

    if (in_array('--order-widgets-extra', $structure_order, true) === true) {
      $css_array[':root']['--order-widgets-extra'] = array_search('--order-widgets-extra', $structure_order);
    }

    if (in_array('--order-footer', $structure_order, true) === true) {
      $css_array[':root']['--order-footer'] = array_search('--order-footer', $structure_order);
    }

    $css       .= origineConfigArrayToCSS($css_array);
    $css_array  = [];

    // Transitions.
    if (isset($_POST['global_css_transition']) === true && $_POST['global_css_transition'] === '1') {
      $css_array['a']['transition']                 = 'all .2s ease-in-out';
      $css_array['a:active, a:hover']['transition'] = 'all .2s ease-in-out';

      $css_array['input[type="submit"], .form-submit, .button']['transition'] = 'all .2s ease-in-out';

      $css_array['input[type="submit"]:hover, .button:hover, .form-submit:hover']['transition'] = 'all .2s ease-in-out';

      $css       .= origineConfigArrayToCSS($css_array);
      $css_array  = [];
    }

    // Reduced motion.
    if (isset($_POST['global_css_transition']) === true && $_POST['global_css_transition'] === '1') {
      $css_array['a']['transition']                                                             = 'none';
      $css_array['a:active, a:hover']['transition']                                             = 'none';
      $css_array['input[type="submit"], .form-submit, .button']['transition']                   = 'none';
      $css_array['input[type="submit"]:hover, .button:hover, .form-submit:hover']['transition'] = 'none';

      $css       .= '@media (prefers-reduced-motion:reduce) {' . origineConfigArrayToCSS($css_array) . '}';
      $css_array  = [];
    }

    $core->blog->settings->origineConfig->put('css', htmlspecialchars($css, ENT_NOQUOTES));

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

    $themes_allowed = ["origine", "origine-mini"];

    if (in_array($theme, $themes_allowed, true) === false) :
      ?>
        <p>
          <?php
          printf(
            __('This plugin is only meant to customize themes of the Origine family. To use it, please <a href="%s">install and activate Origine or Origine Mini</a>.'),
            html::escapeURL($core->adminurl->get('admin.blog.theme'))
          );
          ?>
        </p>
      <?php
    else :
    ?>
      <form action="<?php echo $p_url; ?>" method="post">
        <?php
        // Displays the activation checkbox before all other settings.
        echo '<p>';
        echo form::checkbox('active', true, $settings['active']);
        echo '<label class="classic" for="active">' . $default_settings['active']['title'] . '</label>';
        echo '</p>';

        echo '<p class="form-note">';
        echo $default_settings['active']['description'] . ' ' . __('Default: unchecked.');
        echo '</p>';

        unset($default_settings['active']);

        /**
         * Creates an array which will contain all the settings and there title following the pattern below.
         * 
         * $setting_page_content = [
         *   'section_id_1' => [
         *     'sub_section_id_1' => ['option_id_1', 'option_id_2'],
         *     'sub_section_id_2' => ['option_id_3', 'option_id_4'],
         *     […]
         *   ],
         * ];
         */
        $setting_page_content = [];

        // Gets all setting sections.
        $sections = origineConfigSettings::setting_sections();

        // Puts titles in the settings array.
        foreach($sections as $section_id => $section_data) {
          $setting_page_content[$section_id] = [];
        }

        // Puts all settings in their sections.
        foreach($default_settings as $setting_id => $setting_data) {
          if (option_supported($theme, $setting_data['theme']) === true && $setting_id !== 'global_css') {
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
          echo '<h3>';
          echo $sections[$title_id]['name'];
          echo '</h3>';

          foreach ($section_content as $sub_section_id => $setting_id) {
            echo '<div class="fieldset">';

            // Shows the sub section name, except if its ID is "no-title".
            if (is_string($sub_section_id) === true && $sub_section_id !== 'no-title') {
              echo '<h4>';
              echo $sections[$title_id]['sub_sections'][$sub_section_id];
              echo '</h4>';
            }

            // Displays the option.
            if (is_string($setting_id) === true) {
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
          <?php echo $core->formNonce(); ?>

          <input type="submit" value="<?php echo __('Save'); ?>" />
        </p>
      </form>
    <?php endif; ?>
  </body>
</html>
