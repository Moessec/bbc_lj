<?php

$sms_config = array();

$sms_config['sms_account'] = 'yf_shop';
$sms_config['sms_pass'] = 'yf_shop';

Yf_Registry::set('sms_config', $sms_config);

return $sms_config;
?>
