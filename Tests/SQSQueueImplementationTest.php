<?php

namespace Wowo\QueueBundle\Tests;

use \Guzzle\Service\Resource\Model;
use \Mockery;
use \Wowo\QueueBundle\Implementation\SQSQueueImplementation;

class SQSQueueImplementationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider putBatchDataProvider
     */
    public function putBatch($jobs, $batchCount)
    {
        $tube = 'tube';
        $queueName = 'queueName';

        $q = Mockery::mock($this->getMockBuilder('\Aws\Sqs\SqsClient')->disableOriginalConstructor()->getMock());
        $q->shouldReceive('sendMessage')->never();
        $q->shouldReceive('sendMessageBatch')->times($batchCount);
        $q->shouldReceive('getQueueUrl')->once()->with(['QueueName' => $queueName])->andReturn(new Model(['QueueUrl' => 'queueUrl']));

        $impl = new SQSQueueImplementation($q, [$tube => $queueName]);
        $impl->putBatch($tube, $jobs);
    }

    public function putBatchDataProvider()
    {
        return [
            [range(1, 2), 1],
            [range(1, 10), 1],
            [range(1, 11), 2],
        ];
    }
}
