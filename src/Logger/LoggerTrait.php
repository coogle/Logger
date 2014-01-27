<?php

namespace Logger;

use \Zend\Log\Logger;

trait LoggerTrait
{
    protected function logEvent($message = null, $priority = Logger::INFO) {
    
        if(is_string($priority)) {
            switch(strtoupper($priority)) {
                case 'EMERG':
                    $priority = Logger::EMERG;
                    break;
                case 'ALERT':
                    $priority = Logger::ALERT;
                    break;
                case 'CRIT':
                    $priority = Logger::CRIT;
                    break;
                case 'ERR':
                    $priority = Logger::ERR;
                    break;
                case 'WARN':
                    $priority = Logger::WARN;
                    break;
                case 'NOTICE':
                    $priority = Logger::NOTICE;
                    break;
                case 'INFO':
                    $priority = Logger::INFO;
                    break;
                default:
                case 'DEBUG':
                    $priority = Logger::DEBUG;
                    break;
            }
        }
        
        $eventManager = $this->getServiceLocator()->get('Application')->getEventManager();
        $eventManager->trigger('log', $this, compact('message', 'priority'));
        
        return $this;
    }
}
