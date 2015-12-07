<?php

namespace Wowo\QueueBundle\Command\Helper;

use DateTime;
use Exception;
use Monolog\Logger;
use Symfony\Component\Console\Output\OutputInterface;

class LoggerHelper
{
    protected $logger;

    protected $output;

    protected $timeFormat;

    public function __construct(Logger $logger, OutputInterface $output, $timeFormat = 'c')
    {
        $this->logger = $logger;
        $this->output = $output;
        $this->timeFormat = $timeFormat;
    }

    public function log($message, $level = Logger::INFO, Exception $e = null)
    {
        $now = new DateTime();
        $this->logger->addRecord($level, strip_tags($message), $e ? ['exception' => $e] : []);
        $this->output->writeLn(sprintf('[%s] %s', $now->format($this->timeFormat), $message));
    }
}
