<?php

$sms_config = array();

$sms_config['sms_account'] = 'ucenter';
$sms_config['sms_pass'] = '111111';

Yf_Registry::set('sms_config', $sms_config);

return $sms_config;
?>
