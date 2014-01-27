<?php

namespace Logger;

use Zend\Http\PhpEnvironment\RemoteAddress;

use Zend\EventManager\StaticEventManager;
use Zend\Mvc\MvcEvent;
use Zend\Log\Writer\Db as DbWriter;
use Zend\Log\Writer\Stream as StreamWriter;
use Zend\Log\Logger;
use Zend\Log\Filter\Priority as PriorityFilter;

class Module
{
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\ClassMapAutoloader' => array(
				__DIR__ . '/autoload_classmap.php'
			),
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)
			)
		);
	}

	public function onBootstrap(MvcEvent $e)
	{
		$events = StaticEventManager::getInstance();
		$serviceManager = $e->getApplication()->getServiceManager();
		$appConfig = $serviceManager->get('Config');

		$logger = new Logger();

		if(!isset($appConfig['logger'])) {
			throw new \RuntimeException("Logger not properly configured");
		}

		if(!isset($appConfig['logger']['priority_filter'])) {
			throw new \RuntimeException("You must specify a 'priority_filter' config param");
		}
		
		$logFilter = new PriorityFilter($appConfig['logger']['priority_filter']);
		
		if(!is_null($appConfig['logger']['db_adapter'])) {
		    if((empty($appConfig['logger']['logger_table']))) {
		    	throw new \RuntimeException("You must specify a 'logger_table' config param");
		    }
		    
		    $dbAdapter = $serviceManager->get($appConfig['logger']['db_adapter']);
		    
		    if(!$dbAdapter instanceof \Zend\Db\Adapter\Adapter) {
		    	throw new \RuntimeException("Failed to load database adapter for logger");
		    }
		    
		    $tableMapping = array(
		    		'timestamp' => 'event_date',
		    		'priorityName' => 'priority',
		    		'message' => 'event',
		    		'extra' => array(
		    				'source' => 'source',
		    				'uri' => 'uri',
		    				'ip'  => 'ip',
		    				'session_id' => 'session_id'
		    		)
		    );
		    
		    $logWriter = new DbWriter($dbAdapter, $appConfig['logger']['logger_table'], $tableMapping);
		    
		    $logWriter->addFilter($logFilter);
		    $logger->addWriter($logWriter);
		}
		
		if(isset($appConfig['logger']['log_file']) && !is_null($appConfig['logger']['log_file'])) {
			$streamWriter = new StreamWriter($appConfig['logger']['log_file']);
			$streamWriter->addFilter($logFilter);
			$logger->addWriter($streamWriter);
		}
	
		$request = $e->getApplication()->getRequest();
		$remoteAddress = new RemoteAddress();

		Logger::registerErrorHandler($logger, true);
		Logger::registerExceptionHandler($logger);

		$events->attach("*", 'log', function(\Zend\EventManager\Event $e) use ($logger, $request, $remoteAddress) {
			$targetClass = get_class($e->getTarget());
			$message = $e->getParam('message', "[No Message Provided]");
			$priority = $e->getParam('priority', Logger::INFO);

			$extras = array(
				'source' => $targetClass,
				'uri' => $request->getUriString(),
				'ip' => $remoteAddress->getIpAddress(),
				'session_id' => session_id()
			);

			$logger->log($priority, $message, $extras);
		});
	}

	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}

	public function getServiceConfig()
	{
		return array();
	}
}