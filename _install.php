<?php
/**
 * origineConfig, a plugin to customize themes of the Origine family.
 *
 * @copyright Teddy
 * @copyright GPL-3.0
 */

if (!defined('DC_CONTEXT_ADMIN')) {
  return;
}

include __DIR__ . '/settings.php';

$new_version = $core->plugins->moduleInfo('origineConfig', 'version');
$old_version = $core->getVersion('origineConfig');

if (version_compare($old_version, $new_version, '>=')) {
  return;
}

try {
  $core->blog->settings->addNamespace('origineConfig');

  /**
   * Delete the old database entry which contained all the settings.
   * 
   * To remove after some releases.
   */
  $core->blog->settings->origineConfig->drop('origine_settings');

  // Default settings to define in the database.
  $oc_settings_default = origineConfigSettings::default_settings();

  foreach($oc_settings_default as $setting_id => $setting_data) {
    if ($setting_data['type'] === 'checkbox') {
      $setting_type = 'boolean';
    } else {
      $setting_type = 'string';
    }

    $core->blog->settings->origineConfig->put(
      $setting_id,
      $setting_data['default'],
      $setting_type,
      $setting_data['title'],
      false,
      true
    );
  }

  $core->setVersion('origineConfig', $new_version);

  return true;
} catch (Exception $e) {
  $core->error->add($e->getMessage());
}

return false;
