<?php

namespace Wowo\QueueBundle\Tests;

use Wowo\QueueBundle\QueueManager;
use Wowo\QueueBundle\Implementation\BeanstalkdQueueImplementation;

/**
 * QueueManagerTest 
 * 
 * @package default
 * @version $id$
 * @copyright 
 * @author Wojciech Sznapka <wojciech@sznapka.pl> 
 * @license 
 */
class QueueManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreation()
    {
        $manager = new QueueManager(new BeanstalkdQueueImplementation());
    }
}
