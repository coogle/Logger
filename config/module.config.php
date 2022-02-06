<?php

use Zend\Log\Logger;
use Gelf\Transport\TcpTransport;

return array(
	'logger' => array(
		'db_adapter' => null,
		'logger_table' => 'application_log',
		'priority_filter' => Logger::DEBUG,
		'gelf_hostname' => null,
        'gelf_port' => TcpTransport::DEFAULT_PORT,
        'gelf_facility' => 'ZF2',
	)
);
