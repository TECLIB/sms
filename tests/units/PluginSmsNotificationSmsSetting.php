<?php

namespace tests\units;

use \atoum;

class PluginSmsNotificationSmsSetting extends atoum {

   public function testGetTypeName() {
      $this->string(\PluginSmsNotificationSmsSetting::getTypeName())
         ->isIdenticalTo('SMS followups configuration');
      $this->string(\PluginSmsNotificationSmsSetting::getTypeName(10))
         ->isIdenticalTo('SMS followups configuration');
   }

   public function testGetEnableLabel() {
      $this->string(\PluginSmsNotificationSmsSetting::getEnableLabel())
         ->isIdenticalTo('Enable followups via SMS');
   }

   public function testGetMode() {
      $this->string(\PluginSmsNotificationSmsSetting::getMode())
         ->isIdenticalTo(\Notification_NotificationTemplate::MODE_SMS);
   }

   public function testShowFormConfig() {
      global $CFG_GLPI;

      $instance = new \PluginSmsNotificationSmsSetting();

      $this->variable($CFG_GLPI['notifications_sms'])->isEqualTo(0);
      $out = $instance->showFormConfig();

      $match = strpos($out, 'Notifications are disabled.');
      $this->integer($match)->isGreaterThanOrEqualTo(0);

      $CFG_GLPI['notifications_sms'] = 1;
      $out = $instance->showFormConfig();
      $match = strpos($out, 'Notifications are disabled.');
      $this->boolean($match)->isFalse();

      //rest to defaults
      $CFG_GLPI['notifications_sms'] = 0;
   }
}
