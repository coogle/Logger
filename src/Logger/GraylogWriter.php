<?php

namespace Logger;

use \Zend\Log\Writer\AbstractWriter;
use \Zend\Log\Formatter\FormatterInterface;
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
    
    /**
     * Get formatter
     *
     * @return Formatter\FormatterInterface
     */
    protected function getFormatter()
    {
        return $this->formatter;
    }

    public function doWrite(array $event) {
        $message = $this->getFormatter()->format($event);
        $this->publisher->publish($message);
    }
}
