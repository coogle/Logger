ZF2 Logger Module
===========================

Introduction
------------
This is a relatively simple Logger Module for ZF2. It attaches to the 'log' event allowing it to capture logging events from any module that triggers that event.

Configuration
-------------

The logger class has the following configuration values available to it under the `logger` key as shown:

  array(
	  'logger' => array(
		  'db_adapter' => null,
		  'logger_table' => 'application_log',
		  'priority_filter' => Logger::DEBUG,
		  'log_file' => '/tmp/application.log'
	  )
  );
  
 - `db_adapter` is the database adapter to use (i.e. `Zend\Db\Adapter\Adapter` or such) if logging to a database
 - `logger_table` is the table we are writing log entries to (schema is in sql/create.sql)
 - `priority_filter` the priority to filter against when writing log entries
 - `log_file` the file on the local file system to log events to

Usage
-----

To use the logger, you can trigger a 'log' event, for example as shown below from a class which implements the `ServiceLocatorAwareInterface`:

  $eventManager = $this->getServiceLocator()->get('Application')->getEventManager();
  $eventManager->trigger('log', $this, array('message' => "Testing Logging", 'priority' => Logger::ERR));
  
Alternatively, the Logger module comes with a `Logger\LoggerTrait` trait which can be used (provides a `logEvent(<message>, <priority>)` method which can be used in any class that provides the `ServiceLocatorAwareInterface`.
