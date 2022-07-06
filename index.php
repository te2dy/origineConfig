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

$theme = $core->blog->settings->system->theme;

$default_settings_v2 = origineConfigSettings::default_settings_v2($theme);

if (is_null($core->blog->settings->origineConfig->activation)) {
  try {
    // Adds all default settings values if necessary.
    foreach($default_settings_v2 as $setting_id => $setting_data) {
      $setting_value_default = $setting_data['default'];

      $setting_value_type = 'string';

      if ($setting_data['type'] === 'checkbox') {
        $setting_value_type = 'boolean';
      } else {
        $setting_value_type = 'string';
      }

      $core->blog->settings->origineConfig->put(
        $setting_id,
        $setting_value_default,
        $setting_value_type,
        $setting_title,
        false
      );
    }
    
    $core->blog->triggerBlog();
    http::redirect($p_url);
  } catch (Exception $e) {
    $core->error->add($e->getMessage());
  }
}

// An array or all settings.
$settings = [];

foreach($default_settings_v2 as $setting_id => $setting_data) {
  if ($setting_data['type'] === 'checkbox') {
    $settings[$setting_id] = (boolean) $core->blog->settings->origineConfig->$setting_id;
  } else {
    $settings[$setting_id] = (string) $core->blog->settings->origineConfig->$setting_id;
  }
}

if (!empty($_POST)) {
  try {
    // Saves options.
    $core->blog->settings->addNamespace('origineConfig');

    foreach ($settings as $id => $value) {
      if (is_bool($value) === true && !empty($_POST[$id])) {
        $core->blog->settings->origineConfig->put($id, $_POST[$id]);
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
        foreach($default_settings_v2 as $setting_id => $setting_data) {
          echo '<p>';

          if ($setting_data['type'] === 'checkbox') {
            echo form::checkbox($setting_id, 1, $settings['activation']);
            echo '<label class="classic" for="' . $setting_id . '">' . $setting_data['title'] . '</label>';
          } elseif ($setting_data['type'] === 'select') {
            echo '<label for="' . $setting_id . '">' . $setting_data['title'] . '</label>';
            echo form::combo($setting_id, $setting_data['choices'], $settings[$setting_id]);
          } elseif ($setting_data['type'] === 'text') {
            echo '<label for="' . $setting_id . '">' . $setting_data['title'] . '</label>';
            echo form::field($setting_id, 30, 255, $settings[$setting_id]);
          }

          echo '</p>';

          if ($setting_data['description']) {
            echo '<p class="form-note">';
            echo __('If you do not check this box, your settings will be ignored.');

            if ($setting_data['type'] === 'checkbox') {
              if ($setting_data['default'] === 1) {
                echo ' ' . __('Default: checked.');
              } else {
                echo ' ' . __('Default: unchecked.');
              }
            }

            echo '</p>';
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
