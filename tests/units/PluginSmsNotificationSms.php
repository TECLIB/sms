<?php

namespace tests\units;

use \atoum;

class PluginSmsNotificationSms extends atoum {

   public function testPluginSetup() {
      global $CFG_GLPI;

      $plugin = new \Plugin();
      $plugin->getFromDBbyDir('sms');

      //check if plugin is up & running
      $this->boolean($plugin->isInstalled('sms'))->isTrue();
      $this->boolean($plugin->isActivated('sms'))->isTrue();

      //check if mode has been registered successfully
      //$this->array($CFG_GLPI)->hasKey('notifications_sms');
      $expected = [
         'label'  => 'SMS',
         'from'   => 'sms'
      ];
      $this->array($CFG_GLPI['notifications_modes'])->hasKey('sms');
      $this->array($CFG_GLPI['notifications_modes']['sms'])->isIdenticalTo($expected);
   }
}
