<?php

namespace Wowo\QueueBundle\Tests;

use \Guzzle\Service\Resource\Model;
use \Wowo\QueueBundle\Implementation\SQSQueueImplementation;

class SQSQueueImplementationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function putBatch()
    {
        $jobs = array(1, 2);
        $tube = 'tube';
        $queueName = 'queueName';

        $q = $this->getMockBuilder('\Aws\Sqs\SqsClient')->disableOriginalConstructor()->setMethods(array('sendMessage', 'getQueueUrl'))->getMock();
        $q->expects($this->exactly(count($jobs)))->method('sendMessage');
        $q->expects($this->once())->method('getQueueUrl')->with(['QueueName' => $queueName])->will($this->returnValue(new Model(['QueueUrl' => 'queueUrl'])));

        $impl = new SQSQueueImplementation($q, array($tube => $queueName));
        $impl->putBatch($tube, $jobs);
    }
}
