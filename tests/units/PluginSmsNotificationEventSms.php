<?php

namespace tests\units;

use \atoum;

class PluginSmsNotificationEventSms extends atoum {

   public function testGetTargetField() {
      $data = [];
      $this->string(\PluginSmsNotificationEventSms::getTargetField($data))->isIdenticalTo('phone');
      $this->array($data)->isIdenticalTo(['phone' => null]);

      $data = ['phone' => '+33625885120'];
      $this->string(\PluginSmsNotificationEventSms::getTargetField($data))->isIdenticalTo('phone');
      $this->array($data)->isIdenticalTo(['phone' => '+33625885120']);
   }

   public function testCanCron() {
      $this->boolean(PluginSmsNotificationSms::canCron())->isTrue();
   }
}
