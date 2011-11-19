<?php

namespace Wowo\Bundle\QueueBundle\Tests;

use Wowo\Bundle\QueueBundle\QueueManager;
use Wowo\Bundle\QueueBundle\Implementation\BeanstalkdQueueImplementation;
use lapistano\ProxyObject\ProxyObject;

/**
 * BeantalkdTest 
 * 
 * @package default
 * @version $id$
 * @copyright 
 * @author Wojciech Sznapka <wojciech@sznapka.pl> 
 * @license 
 */
class BeantalkdTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testConfigure 
     * 
     * @access public
     * @return void
     */
    public function testConfigure()
    {
        $opt = array(
            'address' => '127.0.0.1:11300',
        );
        $proxy = new ProxyObject();
        $proxy = $proxy
            ->getProxyBuilder('\Wowo\Bundle\QueueBundle\Implementation\BeanstalkdQueueImplementation')
            ->setProperties(array('pheanstalk', 'tube', 'ignore'))
            ->getProxy();

        $proxy = $this->getProxy();
        $proxy->configure($opt);
        $this->assertInstanceOf('Pheanstalk', $proxy->pheanstalk);
        $this->assertEquals('wowo_default', $proxy->tube);
        $this->assertEquals('default', $proxy->ignore);
    }

    /**
     * testConfigureWithOwnClass 
     * 
     * @access public
     * @return void
     */
    public function testConfigureWithOwnClass()
    {
        $opt = array(
            'address' => '127.0.0.1:11300',
            'ignore' => 'asd',
            'tube'   => 'lol',
            'pheanstalkClass' => '\Wowo\Bundle\QueueBundle\Tests\ExtendedPheanstalk'
        );

        $proxy = $this->getProxy();
        $proxy->configure($opt);
        $this->assertInstanceOf('\Wowo\Bundle\QueueBundle\Tests\ExtendedPheanstalk', $proxy->pheanstalk);
        $this->assertEquals('lol', $proxy->tube);
        $this->assertEquals('asd', $proxy->ignore);
    }

    /**
     * testConfigureWithOwnObject 
     * 
     * @access public
     * @return void
     */
    public function testConfigureWithOwnObject()
    {
        $opt = array(
            'pheanstalkObject' => new ExtendedPheanstalk('127.0.0.1:11300')
        );

        $proxy = $this->getProxy();
        $proxy->configure($opt);
        $this->assertInstanceOf('\Wowo\Bundle\QueueBundle\Tests\ExtendedPheanstalk', $proxy->pheanstalk);
    }

    /**
     * testConfigureWithOwnObject 
     * @expectedException \Wowo\Bundle\QueueBundle\Exception\ConfigurationException
     * 
     * @access public
     * @return void
     */
    public function testConfigureWithOwnObjectAndWrongClass()
    {
        $opt = array(
            'pheanstalkObject' => new \StdClass()
        );

        $proxy = $this->getProxy();
        $proxy->configure($opt);
    }

    /**
     * testConfigureWithoutAddress 
     * @expectedException \Wowo\Bundle\QueueBundle\Exception\ConfigurationException
     * 
     * @access public
     * @return void
     */
    public function testConfigureWithoutAddress()
    {
        $proxy = $this->getProxy();
        $proxy->configure(array());
    }

    /**
     * testConfigureWithEmptyClass 
     * @expectedException \Wowo\Bundle\QueueBundle\Exception\ConfigurationException
     * 
     * @access public
     * @return void
     */
    public function testConfigureWithEmptyClass()
    {
        $proxy = $this->getProxy();
        $proxy->configure(array('pheanstalkClass' => null, 'address' => 'foo'));
    }

    /**
     * testConfigureWithWrongClass
     * @expectedException \Wowo\Bundle\QueueBundle\Exception\ConfigurationException
     * 
     * @access public
     * @return void
     */
    public function testConfigureWithWrongClass()
    {
        $proxy = $this->getProxy();
        $proxy->configure(array('pheanstalkClass' => '\StdClass', 'address' => 'foo'));
    }

    protected function getProxy()
    {
        $proxy = new ProxyObject();
        $proxy = $proxy
            ->getProxyBuilder('\Wowo\Bundle\QueueBundle\Implementation\BeanstalkdQueueImplementation')
            ->setProperties(array('pheanstalk', 'tube', 'ignore'))
            ->getProxy();
        return $proxy;
    }
}

class ExtendedPheanstalk extends \Pheanstalk
{
}
