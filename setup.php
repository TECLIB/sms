<?php
/*
 -------------------------------------------------------------------------
 sms plugin for GLPI
 Copyright (C) 2017 by the sms Development Team.

 https://github.com/pluginsGLPI/sms
 -------------------------------------------------------------------------

 LICENSE

 This file is part of sms.

 sms is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 sms is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with sms. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

define('PLUGIN_SMS_VERSION', '0.0.1');

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_sms() {
   global $PLUGIN_HOOKS, $CFG_GLPI;
   $plugin = new Plugin();

   $PLUGIN_HOOKS['csrf_compliant']['sms'] = true;

   if ($plugin->isActivated('sms')) {
      Notification_NotificationTemplate::registerMode(
         Notification_NotificationTemplate::MODE_SMS,
         __('SMS', 'plugin_sms'),
         'sms'
      );
   }
}


/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_sms() {
   return [
      'name'           => 'sms',
      'version'        => PLUGIN_SMS_VERSION,
      'author'         => '<a href="http://www.teclib.com">Teclib\'</a>',
      'license'        => '',
      'homepage'       => '',
      'minGlpiVersion' => '9.2'
   ];
}

/**
 * Check pre-requisites before install
 * OPTIONNAL, but recommanded
 *
 * @return boolean
 */
function plugin_sms_check_prerequisites() {
   // Strict version check (could be less strict, or could allow various version)
   if (version_compare(GLPI_VERSION, '9.2', 'lt')) {
      if (method_exists('Plugin', 'messageIncompatible')) {
         echo Plugin::messageIncompatible('core', '9.2');
      } else {
         echo "This plugin requires GLPI >= 9.2";
      }
      return false;
   }
   return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_sms_check_config($verbose = false) {
   if (true) { // Your configuration check
      return true;
   }

   if ($verbose) {
      _e('Installed / not configured', 'sms');
   }
   return false;
}
