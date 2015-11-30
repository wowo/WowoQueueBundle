<?php
namespace Wowo\QueueBundle\Implementation;

use Aws\Sqs\SqsClient;
use Wowo\QueueBundle\Implementation\SQSQueue\Message as SqsMessage;
use Wowo\QueueBundle\QueueImplementationInterface;

class SQSQueueImplementation implements QueueImplementationInterface
{

    /**
     * Maximum allowed wait time (in seconds)
     * SQS permits
     */
    const WAIT_TIME_MAX = 20;

    /**
     * @var \Aws\Sqs\SqsClient
     */
    protected $q;

    /**
     * An associative array where the keys are the tube names
     * and the values are the SQS queue names to be written to
     * @var array
     */
    protected $queueNames;

    /**
     * Cache to prevent having to lookup the URL over and over
     * @var array
     */
    protected $tubeToUrlCache = [];

    public function __construct(SqsClient $q, array $queueNames = [])
    {
        $this->q = $q;
        $this->queueNames = $queueNames;
    }

    /**
     * Looks up the SQS Queue name configured for the tube name
     * @param $tube
     * @return mixed|null
     */
    protected function getQueueUrlFor($tube)
    {
        if (!isset($this->queueNames[$tube])) {
            throw new \RuntimeException("Unable to look up queue for tube '$tube'");
        }
        if (!isset($this->tubeToUrlCache[$tube])) {
            $this->tubeToUrlCache[$tube] = $this->q->getQueueUrl(['QueueName' => $this->queueNames[$tube]])->get('QueueUrl');
        }

        return $this->tubeToUrlCache[$tube];
    }

    /**
     * Puts a job in the SQS queue
     * @param $tube
     * @param $job
     * @param null $priority
     * @param null $delay
     */
    public function put($tube, $job, $priority = null, $delay = null, $ttr = null)
    {
        $this->q->sendMessage([
            'QueueUrl' => $this->getQueueUrlFor($tube),
            'MessageBody' => $job,
            'DelaySeconds' => $delay ?: 0,
        ]);
    }

    /**
     * putBatch
     *
     * @param array $jobs
     * @param mixed $priority
     * @param mixed $delay
     * @param mixed $ttr
     * @access public
     * @return void
     */
    public function putBatch($tube, $jobs, $priority = null, $delay = null, $ttr = null)
    {
        // http://docs.aws.amazon.com/AWSSimpleQueueService/latest/APIReference/API_SendMessageBatch.html
        // Delivers up to ten messages to the specified queue
        foreach (array_chunk($jobs, 10) as $chunk) {
            $entries = array_map(function($job) use ($delay) {
                return [
                    'MessageBody' => $job,
                    'DelaySeconds' => $delay ?: 0,
                ];
            }, $chunk);
            $this->q->sendMessageBatch([
                'QueueUrl' => $this->getQueueUrlFor($tube),
                'Entries' => $entries,
            ]);
        }
    }

    /**
     * Reads up to one message from the SQS queue
     * @param $tube
     * @param $secondsToWait
     * @return null|SqsMessage
     */
    public function get($tube, $secondsToWait)
    {
        $qUrl = $this->getQueueUrlFor($tube);
        $messages = $this->q->receiveMessage([
            'QueueUrl' => $qUrl,
            'MaxNumberOfMessages' => 1,
            'WaitTimeSeconds' => $secondsToWait > self::WAIT_TIME_MAX ? self::WAIT_TIME_MAX : $secondsToWait,
        ])->get('Messages');

        return count($messages) ? new SqsMessage($qUrl, $messages[0]) : null;
    }

    /**
     * Effectively a noop
     * @param $tube
     * @param $job
     * @param null $priority
     * @param null $delay
     * @return bool
     */
    public function release($tube, $job, $priority = null, $delay = null)
    {
        return true;
    }

    /**
     * Delete the message from the queue
     * @param $implementationSpecificJobObject
     */
    public function delete($implementationSpecificJobObject)
    {
        /* @var $implementationSpecificJobObject SqsMessage */
        $this->q->deleteMessage([
            'QueueUrl' => $implementationSpecificJobObject->getUrl(),
            'ReceiptHandle' => $implementationSpecificJobObject->get('ReceiptHandle'),
        ]);
    }

}