<?php
namespace Wowo\QueueBundle\Implementation\SQSQueue;

class Message
{

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $response;

    public function __construct($url, array $response)
    {
        $this->url = $url;
        $this->response = $response;

    }

    public function getUrl()
    {
        return $this->url;
    }

    public function get($key)
    {
        return $this->response[$key];
    }

    public function getData()
    {
        return $this->response['Body'];
    }
}