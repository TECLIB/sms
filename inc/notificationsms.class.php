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
 *  NotificationSms
**/
class PluginSmsNotificationSms implements NotificationInterface {

   /**
    * Check data
    *
    * @param mixed $value   The data to check (may differ for every notification mode)
    * @param array $options Optionnal special options (may be needed)
    *
    * @return boolean
   **/
   static function check($value, $options = []) {
      //Does nothing, but we could check if $value is actually what we expect as a phone number to send SMS.
      return true;
   }

   static function testNotification() {
      Session::addMessageAfterRedirect(
         __('SMS notifications are not implemented yet.', 'sms'),
         true,
         WARNING
      );
      return;
      /*$instance = new self();
      $instance->sendNotification([
         '_itemtype'                   => 'NotificationSms',
         '_items_id'                   => 1,
         '_notificationtemplates_id'   => 0,
         '_entities_id'                => 0,
         'fromname'                    => 'TEST',
         'subject'                     => 'Test notification',
         'content_text'                => "Hello, this is a test notification.",
         'to'                          => Session::getLoginUserID()
      ]);*/
   }


   function sendNotification($options=array()) {

      $data = array();
      $data['itemtype']                             = $options['_itemtype'];
      $data['items_id']                             = $options['_items_id'];
      $data['notificationtemplates_id']             = $options['_notificationtemplates_id'];
      $data['entities_id']                          = $options['_entities_id'];

      $data['sendername']                           = $options['fromname'];

      $data['name']                                 = $options['subject'];
      $data['body_text']                            = $options['content_text'];
      $data['recipient']                            = $options['to'];

      $data['mode'] = Notification_NotificationTemplate::MODE_SMS;

      $mailqueue = new QueuedNotification();

      if (!$mailqueue->add(Toolbox::addslashes_deep($data))) {
         Session::addMessageAfterRedirect(__('Error inserting sms notification to queue', 'sms'), true, ERROR);
         return false;
      } else {
         //TRANS to be written in logs %1$s is the to email / %2$s is the subject of the mail
         Toolbox::logInFile("notification",
                            sprintf(__('%1$s: %2$s'),
                                    sprintf(__('An SMS notification to %s was added to queue', 'sms'),
                                            $options['to']),
                                    $options['subject']."\n"));
      }

      return true;
   }
}
