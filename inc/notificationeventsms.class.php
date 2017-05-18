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

class PluginSmsNotificationEventSms implements NotificationEventInterface {
   /**
    * Raise a SMS notification event
    *
    * @param string               $event              Event
    * @param CommonDBTM           $item               Notification data
    * @param array                $options            Options
    * @param string               $label              Label
    * @param array                $data               Notification data
    * @param NotificationTarget   $notificationtarget Target
    * @param NotificationTemplate $template           Template
    * @param boolean              $notify_me          Whether to notify current user
    *
    * @return void
    */
   static public function raise(
      $event,
      CommonDBTM $item,
      array $options,
      $label,
      array $data,
      NotificationTarget $notificationtarget,
      NotificationTemplate $template,
      $notify_me
   ) {
      global $CFG_GLPI;
      if ($CFG_GLPI['notifications_sms']) {
         $entity = $notificationtarget->getEntity();
         $processed    = array();
         $notprocessed = array();

         $targets = getAllDatasFromTable(
            'glpi_notificationtargets',
            "notifications_id = {$data['id']}"
         );

         //Foreach notification targets
         foreach ($targets as $target) {
            //Get all users affected by this notification
            $notificationtarget->addForTarget($target, $options);

            foreach ($notificationtarget->getTargets() as $phone => $users_infos) {
               if ($label
                     || $notificationtarget->validateSendTo($event, $users_infos, $notify_me)) {
                  //If the user have not yet been notified
                  if (!isset($processed[$users_infos['language']][$phone])) {
                     //If ther user's language is the same as the template's one
                     if (isset($notprocessed[$users_infos['language']]
                                                   [$phone])) {
                        unset($notprocessed[$users_infos['language']]
                                                   [$phone]);
                     }
                     $options['item'] = $item;
                     if ($tid = $template->getTemplateByLanguage($notificationtarget,
                                                                  $users_infos, $event,
                                                                  $options)) {
                        //Send notification to the user
                        if ($label == '') {
                           $send_data = $template->getDataToSend(
                              $notificationtarget,
                              $tid,
                              $phone,
                              $users_infos,
                              $options
                           );
                           $send_data['_notificationtemplates_id'] = $data['notificationtemplates_id'];
                           $send_data['_itemtype']                 = $item->getType();
                           $send_data['_items_id']                 = $item->getID();
                           $send_data['_entities_id']              = $entity;
                           $send_data['mode']                      = $data['mode'];

                           Notification::send($send_data);
                        } else {
                           $notificationtarget->getFromDB($target['id']);
                           echo "<tr class='tab_bg_2'><td>".$label."</td>";
                           echo "<td>".$notificationtarget->getNameID()."</td>";
                           echo "<td>".sprintf(__('%1$s (%2$s)'), $template->getName(),
                                                $users_infos['language'])."</td>";
                           echo "<td>".$options['mode']."</td>";
                           echo "<td>".$$phone."</td>";
                           echo "</tr>";
                        }
                        $processed[$users_infos['language']][$phone]
                                                                  = $users_infos;

                     } else {
                        $notprocessed[$users_infos['language']][$phone]
                                                                     = $users_infos;
                     }
                  }
               }
            }
         }

         unset($processed);
         unset($notprocessed);
      }
   }


   static public function getTargetField(&$data) {
      $field = 'phone';

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
