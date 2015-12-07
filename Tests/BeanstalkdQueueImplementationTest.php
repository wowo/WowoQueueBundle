<?php

namespace Wowo\QueueBundle\Tests;

use Mockery;
use Wowo\QueueBundle\Implementation\BeanstalkdQueueImplementation;

class BeanstalkdQueueImplementationTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @expectedException \Wowo\QueueBundle\Exception\ConfigurationException
     */
    public function testNotListeningToService()
    {
        $pheanstalk = Mockery::mock('\Pheanstalk_PheanstalkInterface');
        $pheanstalk->shouldReceive('getConnection->isServiceListening')->andThrow('\Wowo\QueueBundle\Exception\ConfigurationException');
        new BeanstalkdQueueImplementation($pheanstalk);
    }
}
