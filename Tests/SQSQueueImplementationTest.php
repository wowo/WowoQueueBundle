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

        $q = $this->getMockBuilder('\Aws\Sqs\SqsClient')->disableOriginalConstructor()->setMethods(['sendMessage', 'sendMessageBatch', 'getQueueUrl'])->getMock();
        $q->expects($this->never())->method('sendMessage');
        $q->expects($this->exactly($batchCount))->method('sendMessageBatch');
        $q->expects($this->once())->method('getQueueUrl')->with(['QueueName' => $queueName])->will($this->returnValue(new Model(['QueueUrl' => 'queueUrl'])));

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
