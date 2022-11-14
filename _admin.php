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

// Adds a link to the menu.
dcCore::app()->menu['Plugins']->addItem(
    __('admin-title'),
    dcCore::app()->adminurl->get('admin.plugin.origineConfig'),
    dcPage::getPF('origineConfig/img/icon.svg'),
    preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.origineConfig')) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check('admin', dcCore::app()->blog->id)
);

// Lets the user add a link in the dashboard.
dcCore::app()->addBehavior(
    'adminDashboardFavoritesV2',
    function (dcFavorites $favs) {
        $favs->register(
            'origineConfig',
            [
            'title'       => __('admin-title'),
            'url'         => dcCore::app()->adminurl->get('admin.plugin.origineConfig'),
            'small-icon'  => dcPage::getPF('origineConfig/img/icon.svg'),
            'large-icon'  => dcPage::getPF('origineConfig/img/icon.svg'),
            'permissions' => dcCore::app()->auth->check('admin', dcCore::app()->blog->id)
            ]
        );
    }
);
