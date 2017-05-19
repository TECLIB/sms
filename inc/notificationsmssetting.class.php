<?php
/**
 * ---------------------------------------------------------------------
 * GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2015-2017 Teclib' and contributors.
 *
 * http://glpi-project.org
 *
 * based on GLPI - Gestionnaire Libre de Parc Informatique
 * Copyright (C) 2003-2014 by the INDEPNET Development Team.
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * GLPI is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * GLPI is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 *  This class manages the sms notifications settings
 */
class PluginSmsNotificationSmsSetting extends NotificationSetting {


   static function getTypeName($nb=0) {
      return __('SMS followups configuration', 'sms');
   }


   public function getEnableLabel() {
      return __('Enable followups via SMS', 'sms');
   }


   static public function getMode() {
      return NotificationTemplateTemplate::MODE_SMS;
   }


   function showFormConfig($options = []) {
      global $CFG_GLPI;

      $conf = Config::getConfigurationValues('plugin:sms');
      $params = [
         'display'   => true
      ];
      $params = array_merge($params, $options);

      $out = "<form action='".Toolbox::getItemTypeFormURL(__CLASS__)."' method='post'>";
      $out .= Html::hidden('config_context', ['value' => 'plugin:sms']);
      $out .= "<div>";
      $out .= "<input type='hidden' name='id' value='1'>";
      $out .= "<table class='tab_cadre_fixe'>";
      $out .= "<tr class='tab_bg_1'><th colspan='4'>"._n('SMS notification', 'SMS notifications', Session::getPluralNumber(), 'sms')."</th></tr>";

      if ($CFG_GLPI['notifications_sms']) {
         //TODO
         $out .= "<tr><td colspan='4'>" . __('SMS notifications are not implemented yet.', 'sms') .  "</td></tr>";
      } else {
         $out .= "<tr><td colspan='4'>" . __('Notifications are disabled.')  . " <a href='{$CFG_GLPI['root_doc']}/front/setup.notification.php'>" . _('See configuration') .  "</td></tr>";
      }
      $options['candel']     = false;
      if ($CFG_GLPI['notifications_sms']) {
         $options['addbuttons'] = array('test_sms_send' => __('Send a test SMS to you', 'sms'));
      }

      if (defined('PLUGIN_SMS_UNIT_TESTS')) {
         return $out;
      }
      //Ignore display parameter since showFormButtons is now ready :/ (from all but tests)
      echo $out;

      $this->showFormButtons($options);

      /*if ($params['display']) {
         echo $out;
      } else {
         eturn $out
      }*/
   }

}
