<?php

namespace Wowo\QueueBundle;

/**
 * Proxies queue implementation
 * 
 * @uses QueueInterface
 * @package default
 * @version $id$
 * @copyright 
 * @author Wojciech Sznapka <wojciech@sznapka.pl> 
 * @license 
 */
class QueueManager 
{
    /**
     * Concrete implementation of queue mechanizm
     */
    protected $implementation;

    protected $tube;

    /**
     * The constructor, gets implementation as a param
     * 
     * @param QueueInterface $implementation 
     * @access public
     * @return void
     */
    public function __construct(QueueInterface $implementation)
    {
        $this->implementation = $implementation;
    }

    /**
     * Sset tube
     *
     * @param string $tube tube
     */
    public function setTube($tube)
    {
        $this->tube = $tube;
    }

    /**
     * Gets tube
     * 
     * @return string
     */
    public function getTube()
    {
        return $this->tube;
    }

    public function put($job, $priority = null, $delay = null)
    {
        return $this->implementation->put($this->tube, $job, $priority, $delay);
    }

    public function get()
    {
        return $this->implementation->get($this->tube);
    }

    public function delete($implementationSpecyficJobObject)
    {
        return $this->implementation->delete($implementationSpecyficJobObject);
    }
}
