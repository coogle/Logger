<?php

use Zend\Log\Logger;
use Zend\Log\Writer\Db;

return array(
	'logger' => array(
		'db_adapter' => null,
		'logger_table' => 'application_log',
		'priority_filter' => Logger::DEBUG,
		'log_file' => '/tmp/application.log'
	)
);
