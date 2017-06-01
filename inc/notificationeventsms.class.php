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

class PluginSmsNotificationEventSms extends NotificationEventAbstract implements NotificationEventInterface {

   static public function getTargetFieldName() {
      return 'phone';
   }

   static public function getTargetField(&$data) {
      $field = self::getTargetFieldName();

      if (!isset($data[$field])
         && isset($data['users_id'])) {
         // No phone set: get one for user
         $user = new user();
         $user->getFromDB($data['users_id']);

         $phone_fields = ['mobile', 'phone', 'phone2'];
         foreach ($phone_fields as $phone_field) {
            if (isset($user->fields[$phone_field]) && !empty($user->fields[$phone_field])) {
               $data[$field] = $user->fields[$phone_field];
               break;
            }
         }
      }

      if (!isset($data[$field])) {
         //Missing field; set to null
         $data[$field] = null;
      }

      return $field;
   }


   static public function canCron() {
      return true;
   }


   static public function getAdminData() {
      //no phone available for global admin right now
      return false;
   }


   static public function getEntityAdminsData($entity) {
      global $DB, $CFG_GLPI;

      $iterator = $DB->request([
         'FROM'   => 'glpi_entities',
         'WHERE'  => ['id' => $entity]
      ]);

      $admins = [];

      while ($row = $iterator->next()) {
         $admins[] = [
            'language'  => $CFG_GLPI['language'],
            'phone'     => $row['phone_number']
         ];
      }

      return $admins;
   }


   static public function send(array $data) {
      throw new \RuntimeException('Not yet implemented!');
   }
}
