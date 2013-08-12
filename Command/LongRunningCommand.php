<?php

namespace Wowo\QueueBundle\Command;

use BadMethodCallException;
use DateInterval;
use DateTime;
use Exception;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wowo\QueueBundle\Command\Helper\LoggerHelper;
use stdClass;

abstract class LongRunningCommand extends ContainerAwareCommand
{
    const RECONNECT_INTERVAL = 'PT1H'; // we reconnect with database for someintervals, to avoid 'MySQL Server gone away' problem
    const SECONDS_TO_BE_RELEASED_FOR = 60;
    const SECONDS_TO_WAIT_FOR_QUEUE = 60;

    protected $nextReconnectDate;
    protected $queue;
    protected $logger;

    abstract protected function doJob(stdClass $job);

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->nextReconnectDate = new DateTime();
        $this->nextReconnectDate->add(new DateInterval(self::RECONNECT_INTERVAL));

        $this->logger = new LoggerHelper($this->getContainer()->get('logger'), $output);
        $this->logger->log(sprintf('<info>%s</info> is starting', $this->getName()));

        while (1) {
            $this->externalResourcesManagement();
            try {
                if (null == $this->queue) {
                    throw new BadMethodCallException('Queue has not been set');
                }
                $rawJob = $this->queue->get(self::SECONDS_TO_WAIT_FOR_QUEUE);
                if ($rawJob) {
                    $this->doJob(json_decode($rawJob->getData(), false));
                    $this->queue->delete($rawJob);
                }
            } catch (Exception $e) {
                if (isset($rawJob)) {
                    $this->queue->release($rawJob, null, self::SECONDS_TO_BE_RELEASED_FOR);
                }
                $this->logger->log(sprintf('<error>%s</error> occured doing <error>%s</error>, message: %s',
                    get_class($e), $this->getName(), $e->getMessage()), Logger::ERROR, $e);
            }
        }
    }

    protected function externalResourcesManagement()
    {
        if ($this->nextReconnectDate < new DateTime()) {
            $this->logger->log(sprintf('<info>%s</info> Reconnecting to database', $this->getName()));

            $this->getContainer()->get('doctrine.orm.entity_manager')->getConnection()->close();
            $this->getContainer()->get('doctrine.orm.entity_manager')->getConnection()->connect();

            $this->nextReconnectDate = $this->nextReconnectDate->add(new DateInterval(self::RECONNECT_INTERVAL));
        }
    }
}
