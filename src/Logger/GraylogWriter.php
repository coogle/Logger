<?php

namespace Logger;

use \Zend\Log\Writer\AbstractWriter;
use \Gelf\Publisher as GELFMessagePublisher;

class GraylogWriter extends AbstractWriter
{
    private $publisher;
    protected $formatter;

    public function __construct($facility, $hostname, $port) {
        $transport = new \Gelf\Transport\TcpTransport($hostname, $port);
        $this->publisher = new GELFMessagePublisher($transport);
        $this->formatter = new \Logger\GelfFormatter($facility);
    }
    
    public function doWrite(array $event) {
        $message = $this->formatter->format($event);
        $this->publisher->publish($message);
    }
}
