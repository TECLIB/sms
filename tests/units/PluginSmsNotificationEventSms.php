<?php

namespace tests\units;

use \atoum;

class PluginSmsNotificationEventSms extends \DbTestCase {

   public function testGetTargetField() {
      $data = [];
      $this->string(\PluginSmsNotificationEventSms::getTargetField($data))->isIdenticalTo('phone');
      $this->array($data)->isIdenticalTo(['phone' => null]);

      $data = ['phone' => '+33625885120'];
      $this->string(\PluginSmsNotificationEventSms::getTargetField($data))->isIdenticalTo('phone');
      $this->array($data)->isIdenticalTo(['phone' => '+33625885120']);

      $user = new \User();
      $phones = [
         'name'   => 'The user name',
         'phone2' => '+33201020405',
         'phone'  => '+33101020405',
         'mobile' => '+33601020405'
      ];

      $user->add($phones);
      $data = ['users_id' => $user->getID()];
      $this->string(\PluginSmsNotificationEventSms::getTargetField($data))->isIdenticalTo('phone');
      $this->array($data)->isIdenticalTo(['users_id' => $user->getID(), 'phone' => '+33601020405']);

      unset($phones['mobile']);
      $phones['name'] = 'Another user name';
      $user->add($phones);
      $data = ['users_id' => $user->getID()];
      $this->string(\PluginSmsNotificationEventSms::getTargetField($data))->isIdenticalTo('phone');
      $this->array($data)->isIdenticalTo(['users_id' => $user->getID(), 'phone' => '+33101020405']);

      unset($phones['phone']);
      $phones['name'] = 'Yet another one';
      $user->add($phones);
      $data = ['users_id' => $user->getID()];
      $this->string(\PluginSmsNotificationEventSms::getTargetField($data))->isIdenticalTo('phone');
      $this->array($data)->isIdenticalTo(['users_id' => $user->getID(), 'phone' => '+33201020405']);
   }

   public function testCanCron() {
      $this->boolean(\PluginSmsNotificationEventSms::canCron())->isTrue();
   }
}
