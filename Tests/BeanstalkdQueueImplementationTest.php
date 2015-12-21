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
     * @test
     */
    public function putBatch()
    {
        $jobs = [1, 2];
        $tube = 'tube';
        $pheanstalk = Mockery::mock('\Pheanstalk_PheanstalkInterface');
        $pheanstalk->shouldReceive('getConnection->isServiceListening')->andReturn(true);
        $pheanstalk->shouldReceive('useTube')->with($tube)->andReturn($pheanstalk);
        $pheanstalk->shouldReceive('put')->times(count($jobs));
        $impl = new BeanstalkdQueueImplementation($pheanstalk);
        $impl->putBatch($tube, $jobs);
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
