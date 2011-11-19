<?php

namespace Wowo\Bundle\QueueBundle\Implementation;

use Wowo\Bundle\QueueBundle\QueueInterface;

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
    public function configure(array $options)
    {
    }

    public function put($job)
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

}
