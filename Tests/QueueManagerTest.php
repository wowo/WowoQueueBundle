<?php

namespace Wowo\QueueBundle\Tests;

use \Wowo\QueueBundle\QueueManager;
use \Mockery;

class QueueManagerTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testGet()
    {
        $impl = Mockery::mock('\Wowo\QueueBundle\QueueImplementationInterface');
        $impl->shouldReceive('get')->once()->with('the-tube', Mockery::any())->andReturn('{}');
        $queue = new QueueManager($impl);
        $queue->setTube('the-tube');

        $this->assertEquals('{}', $queue->get());
    }

    public function testPutAndRelease()
    {
        $impl = Mockery::mock('\Wowo\QueueBundle\QueueImplementationInterface');
        $impl->shouldReceive('put')->once()->with('the-tube', 'a thing', 1, 100, 60)->andReturn(true);
        $impl->shouldReceive('release')->once()->with('the-tube', 'other thing', 1, 100)->andReturn(true);
        $queue = new QueueManager($impl);
        $queue->setTube('the-tube');

        $this->assertTrue($queue->put('a thing', 1, 100, 60));
        $this->assertTrue($queue->release('other thing', 1, 100));
    }

    public function testDelete()
    {
        $impl = Mockery::mock('\Wowo\QueueBundle\QueueImplementationInterface');
        $impl->shouldReceive('delete')->once()->with('a job')->andReturn(true);
        $queue = new QueueManager($impl);

        $this->assertTrue($queue->delete('a job'));
    }
}
