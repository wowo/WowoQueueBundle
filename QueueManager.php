<?php

namespace Wowo\Bundle\QueueBundle;

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
class QueueManager implements QueueInterface
{
    /**
     * Concrete implementation of queue mechanizm
     */
    protected $implementation;

    /**
     * Symfony2 service container (or any other container implementation you may use with your framework)
     */
    protected $serviceContainer;

    /**
     * The constructor, gets implementation as a param
     * 
     * @param QueueInterface $implementation 
     * @access public
     * @return void
     */
    public function __construct(QueueInterface $implementation, $serviceContainer = null)
    {
        $this->implementation   = $implementation;
        $this->serviceContainer = $serviceContainer;
    }

    public function configure(array $options)
    {
        return $this->implementation->configure($options);
    }

    public function put($job, $priority = null, $delay = null)
    {
        return $this->implementation->put($job, $priority, $delay);
    }

    public function get()
    {
        return $this->implementation->get();
    }

    public function delete($implementationSpecyficJobObject)
    {
        return $this->implementation->delete($implementationSpecyficJobObject);
    }
}
