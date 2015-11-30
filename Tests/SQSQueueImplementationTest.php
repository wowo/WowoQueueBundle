<?php

namespace Wowo\QueueBundle\Tests;

use \Guzzle\Service\Resource\Model;
use \Wowo\QueueBundle\Implementation\SQSQueueImplementation;

class SQSQueueImplementationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider data_putBatch
     */
    public function putBatch($jobs, $batchCount)
    {
        $tube = 'tube';
        $queueName = 'queueName';

        $q = $this->getMockBuilder('\Aws\Sqs\SqsClient')->disableOriginalConstructor()->setMethods(array('sendMessage', 'sendMessageBatch', 'getQueueUrl'))->getMock();
        $q->expects($this->never())->method('sendMessage');
        $q->expects($this->exactly($batchCount))->method('sendMessageBatch');
        $q->expects($this->once())->method('getQueueUrl')->with(['QueueName' => $queueName])->will($this->returnValue(new Model(['QueueUrl' => 'queueUrl'])));

        $impl = new SQSQueueImplementation($q, array($tube => $queueName));
        $impl->putBatch($tube, $jobs);
    }

    public function data_putBatch()
    {
        return array(
            array(range(1, 2), 1),
            array(range(1, 10), 1),
            array(range(1, 11), 2),
        );
    }
}
