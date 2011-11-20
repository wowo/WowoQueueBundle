<?php

namespace Wowo\Bundle\QueueBundle\Implementation;

use Wowo\Bundle\QueueBundle\QueueInterface;
use Wowo\Bundle\QueueBundle\Exception\ConfigurationException;

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
class BeanstalkdQueueImplementation implements QueueInterface
{
    protected $pheanstalk;
    protected $tube;
    protected $ignore;

    /**
     * configures implementation object 
     * 
     * @param array $options 
     * @access public
     * @return void
     */
    public function configure(array $options)
    {
        $default = array(
            'address' => null,
            'tube'    => 'wowo_default',
            'ignore'  => 'default',
            'pheanstalkClass'  => 'Pheanstalk',
            'pheanstalkObject' => null,
        );
        $options = array_merge($default, $options);
        $this->tube    = $options['tube'];
        $this->ignore  = $options['ignore'];

        if (null != $options['pheanstalkObject']) {
            $this->pheanstalk = $options['pheanstalkObject'];
        } else {
            if (null == $options['address']) {
                throw new ConfigurationException("Beanstalkd address can't be null");
            }
            if (null == $options['pheanstalkClass']) {
                throw new ConfigurationException("Pheanstalk class can't be null");
            }
            $klass = $options['pheanstalkClass'];
            $this->pheanstalk = new $klass($options['address']);
        }
        if (!$this->pheanstalk instanceof \Pheanstalk) {
            $this->pheanstalk = null;
            throw new ConfigurationException("Invalid object passed as a pheanstalkObject");
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
    public function put($job, $priority = null, $delay = null)
    {
        return $this
            ->pheanstalk
            ->useTube($this->tube)
            ->put($job, $priority ?: \Pheanstalk::DEFAULT_PRIORITY, $delay);
    }

    /**
     * get 
     * 
     * @access public
     * @return void
     */
    public function get()
    {
        return $this
            ->pheanstalk
            ->watch($this->tube)
            ->ignore($this->ignore)
            ->reserve();
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

    protected function getAllMessages()
    {
    }
}
