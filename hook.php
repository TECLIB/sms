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

/**
 * Plugin install process
 *
 * @return boolean
 */
function plugin_sms_install() {
   Config::setConfigurationValues('core', ['notifications_sms' => 0]);
   Config::setConfigurationValues(
      'plugin:sms', [
      ]
   );

   return true;
}

/**
 * Plugin uninstall process
 *
 * @return boolean
 */
function plugin_sms_uninstall() {
   $config = new Config();
   $config->deleteByCriteria(['context' => 'plugin:sms']);
   $config->deleteConfigurationValues('core', ['notifications_sms']);

   return true;
}
