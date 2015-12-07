<?php

namespace Wowo\QueueBundle\Implementation;

use Pheanstalk_PheanstalkInterface;
use Wowo\QueueBundle\Exception\ConfigurationException;
use Wowo\QueueBundle\QueueImplementationInterface;

/**
 * Unified Beanstalkd implementation which hides Pheanstalk usage
 *
 * @uses QueueInterface
 * @package default
 * @version $id$
 * @copyright
 * @author Wojciech Sznapka <wojciech@sznapka.pl>
 * @license
 */
class BeanstalkdQueueImplementation implements QueueImplementationInterface
{
    /**
     * @var Pheanstalk_PheanstalkInterface 
     */
    protected $pheanstalk;

    /**
     * @var string 
     */
    protected $ignore;

    public function __construct(Pheanstalk_PheanstalkInterface $pheanstalk, $ignore = null)
    {
        $this->pheanstalk = $pheanstalk;
        $this->ignore = $ignore;

        if (!$this->pheanstalk->getConnection()->isServiceListening()) {
            throw new ConfigurationException(sprintf('Beanstalkd server is not listening at %s', $this->pheanstalk->getConnection()->getHost()));
        }
    }

    /**
     * put
     *
     * @param $tube
     * @param mixed $job
     * @param mixed $priority
     * @param mixed $delay
     * @param mixed $ttr
     * @access public
     */
    public function put($tube, $job, $priority = null, $delay = null, $ttr = null)
    {
        return $this
            ->pheanstalk
            ->useTube($tube)
            ->put(
                $job,
                $priority ?: \Pheanstalk_Pheanstalk::DEFAULT_PRIORITY,
                $delay,
                $ttr ?: \Pheanstalk_Pheanstalk::DEFAULT_TTR
            );
    }

    /**
     * get
     *
     * @access public
     * @param $tube
     * @param null $secondsToWait
     */
    public function get($tube, $secondsToWait = null)
    {
        return $this
            ->pheanstalk
            ->watch($tube)
            ->ignore($this->ignore)
            ->reserve($secondsToWait);
    }

    public function release($tube, $job, $priority = null, $delay = null)
    {
        return $this
            ->pheanstalk
            ->useTube($tube)
            ->release($job, $priority, $delay);
    }

    /**
     * delete
     *
     * @param mixed $implementationSpecificJobObject
     * @access public
     */
    public function delete($implementationSpecificJobObject)
    {
        return $this
            ->pheanstalk
            ->delete($implementationSpecificJobObject);
    }
}
