<?php

namespace Wowo\QueueBundle\Tests;

use \Wowo\QueueBundle\Implementation\BeanstalkdQueueImplementation;
use \Mockery;

class BeanstalkdQueueImplementationTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException \Wowo\QueueBundle\Exception\ConfigurationException
     */
    public function testNotListenningToService()
    {
        $pheanstalk = Mockery::mock('\Pheanstalk_PheanstalkInterface');
        $pheanstalk->shouldReceive('getConnection->isServiceListening')->andThrow('\Wowo\QueueBundle\Exception\ConfigurationException');
        new BeanstalkdQueueImplementation($pheanstalk);
    }
}
