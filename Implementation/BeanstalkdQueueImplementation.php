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

    public function put($job, $delay)
    {
    }

    public function get()
    {
    }

    public function delete()
    {
    }

    public function clear()
    {
    }

    protected function getAllMessages()
    {
    }
}
