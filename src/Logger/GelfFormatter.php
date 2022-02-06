<?php

namespace Logger;

use \Zend\Log\Formatter\Base;
use \Gelf\Message as GELFMessage;

class GelfFormatter extends Base
{
    private $facility = 'ZF2';

    public function __construct($facility = null) {
        if (!is_null($facility)) {
            $this->facility = (string) $facility;
        }
    }

    /**
     * Returns a GELFMessage instance to be used with a GELFMessagePublisher
     *
     * @return GELFMessage
     */
    public function format($event) {
        $message = new GELFMessage();

        if (isset($event['priority'])) {
            $message->setLevel($event['priority']);
        } else if (isset($event['errno'])) {
            //TODO preciso???
            $message->setLevel($event['errno']);
        }

        $message->setFullMessage($event['message']);
        $message->setShortMessage($event['message']);
        if (isset($event['full'])) $message->setFullMessage($event['full']);
        if (isset($event['short'])) $message->setShortMessage($event['short']);

        if (isset($event['extra']['file'])) $message->setFile($event['extra']['file']);
        if (isset($event['extra']['line'])) $message->setLine($event['extra']['line']);

        if (isset($event['version'])) $message->setVersion($event['version']);

        $message->setFacility($this->facility);

        $timestamp = $event['timestamp'];
        if ($event['timestamp'] && ($event['timestamp'] instanceof \DateTime)) {
            $timestamp = $event['timestamp']->getTimestamp();
        }
        $message->setTimestamp($timestamp);

        foreach ($event as $k => $v) {
            if (!in_array($k, ['message', 'priority', 'errno', 'full', 'short',
                'file', 'line', 'version', 'facility', 'timestamp'])) {
                $message->setAdditional($k, $v);
            }
        }

        return $message;
    }
}