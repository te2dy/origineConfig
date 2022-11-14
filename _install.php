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

$new_version = dcCore::app()->plugins->moduleInfo('origineConfig', 'version');
$old_version = dcCore::app()->getVersion('origineConfig');

if (version_compare($old_version, $new_version, '>=')) {
    return;
}

try {
    dcCore::app()->blog->settings->addNamespace('origineConfig');

    /**
     * Delete the old database entry which contained all the settings.
     *
     * To remove after some releases.
     *
     * @since origineConfig 2.1
     */
    dcCore::app()->blog->settings->origineConfig->drop('origine_settings');

    // Default settings to define in the database.
    $oc_settings_default = origineConfigSettings::default_settings();

    foreach($oc_settings_default as $setting_id => $setting_data) {
        if ($setting_data['type'] === 'checkbox') {
            $setting_type = 'boolean';
        } elseif ($setting_data['type'] === 'select_int') {
            $setting_type = 'integer';
        } else {
            $setting_type = 'string';
        }

        dcCore::app()->blog->settings->origineConfig->put(
            $setting_id,
            $setting_data['default'],
            $setting_type,
            $setting_data['title'],
            false,
            true
        );
    }

    // Removes unused settings if still exists.
    $settings_to_unset = origineConfigSettings::settings_to_unset();

    foreach ($settings_to_unset as $setting_id) {
        dcCore::app()->blog->settings->origineConfig->dropEvery($setting_id, true);
    }

    // Sets the new version number of the plugin.
    dcCore::app()->setVersion('origineConfig', $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
