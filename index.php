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
        1,
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
        $settings[$setting_id]
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
        if ($default_settings[$setting_id]['default'] === 1) {
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

$theme = $core->blog->settings->system->theme;

$default_settings = origineConfigSettings::default_settings($theme);

$core->blog->settings->addNamespace('origineConfig');

// Adds all default settings values if necessary.
foreach($default_settings as $setting_id => $setting_data) {
  if (!$core->blog->settings->origineConfig->$setting_id) {
    if ($setting_data['type'] === 'checkbox') {
      $setting_type = 'boolean';
    } elseif ($setting_data['type'] === 'select_int') {
      $setting_type = 'int';
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

    $core->blog->triggerBlog();

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
        echo form::checkbox('activation', 1, $settings['activation']);
        echo '<label class="classic" for="activation">' . $default_settings['activation']['title'] . '</label>';
        echo '</p>';

        echo '<p class="form-note">';
        echo $default_settings['activation']['description'] . ' ' . __('Default: unchecked.');
        echo '</p>';

        unset($default_settings['activation']);

        // Creates an array which will contain all the settings and there title.
        $setting_page_content = [];

        // Gets all setting sections.
        $sections = origineConfigSettings::setting_sections();

        // Puts titles in the settings array.
        foreach($sections as $section_id => $section_data) {
          $setting_page_content[$section_id] = [];
        }

        // Puts all settings in their sections.
        foreach($default_settings as $setting_id => $setting_data) {
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

        // Removes titles when there are associated with any setting.
        $setting_page_content = array_filter($setting_page_content);

        // Displays the title of each sections and put the settings inside.
        foreach ($setting_page_content as $title_id => $section_content) {
          echo '<h3>';
          echo $sections[$title_id]['name'];
          echo '</h3>';

          foreach ($section_content as $sub_section_id => $setting_id) {
            echo '<div class="fieldset">';

            if (is_string($sub_section_id) === true) {
              echo '<h4>';
              echo $sections[$title_id]['sub_sections'][$sub_section_id];
              echo '</h4>';
            }

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
