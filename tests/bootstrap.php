<?php
// fix empty CFG_GLPI on boostrap; see https://github.com/sebastianbergmann/phpunit/issues/325
global $CFG_GLPI;

define('GLPI_ROOT', dirname(dirname(dirname(__DIR__))));
define('GLPI_CONFIG_DIR', GLPI_ROOT . '/tests');
define('PLUGIN_SMS_UNIT_TESTS', true);
include GLPI_ROOT . "/inc/includes.php";

//install plugin
$plugin = new \Plugin();
$plugin->getFromDBbyDir('sms');
if (!$plugin->isInstalled('sms')) {
   call_user_func([$plugin, 'install'], $plugin->getID());
}
if (!$plugin->isActivated('sms')) {
   call_user_func([$plugin, 'activate'], $plugin->getID());
}

include_once __DIR__ . '/DbTestCase.php';
