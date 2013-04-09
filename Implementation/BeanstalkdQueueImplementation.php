<?php

namespace Wowo\QueueBundle\Implementation;

use Wowo\QueueBundle\QueueImplementationInterface;
use Wowo\QueueBundle\Exception\ConfigurationException;
use Pheanstalk_PheanstalkInterface;

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
    protected $pheanstalk;

    protected $ignore;

    public function __construct(Pheanstalk_PheanstalkInterface $pheanstalk, $ignore = null)
    {
        $this->pheanstalk = $pheanstalk;
        $this->ignore = $ignore;

        if (!$this->pheanstalk->getConnection()->isServiceListening()) {
            throw new ConfigurationException(sprintf('Beanstalkd server is not listening at %s', $options['address']));
        }
    }

    /**
     * put 
     * 
     * @param mixed $job 
     * @param mixed $priority 
     * @param mixed $delay 
     * @access public
     * @return void
     */
    public function put($tube, $job, $priority = null, $delay = null)
    {
        return $this
            ->pheanstalk
            ->useTube($tube)
            ->put($job, $priority ?: \Pheanstalk_Pheanstalk::DEFAULT_PRIORITY, $delay);
    }

    /**
     * get 
     * 
     * @access public
     * @return void
     */
    public function get($tube)
    {
        return $this
            ->pheanstalk
            ->watch($tube)
            ->ignore($this->ignore)
            ->reserve();
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
     * @param mixed $implementationSpecyficJobObject 
     * @access public
     * @return void
     */
    public function delete($implementationSpecyficJobObject)
    {
        return $this
            ->pheanstalk
            ->delete($implementationSpecyficJobObject);
    }
}
