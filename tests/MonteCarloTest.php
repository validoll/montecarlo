<?php

include_once '../class/MonteCarlo.class.php';

define('PID_CURR', 2222);
define('PID_PARENT', 1111);
define('PID_NEW', 3333);

/**
 * MonteCarlo class test
 */
class MonteCarloTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MonteCarlo
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new MonteCarlo(PID_CURR, PID_PARENT);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->object);
    }

    /**
     * @covers MonteCarlo::setPid
     */
    public function testSetPid()
    {
        $this->assertFalse($this->object->setPid(PID_NEW));
    }

    /**
     * @covers MonteCarlo::setPid
     * @expectedException InvalidArgumentException
     */
    public function testSetPidWrongType()
    {
        $this->object->setPid('WRONG PID TYPE');
    }

    /**
     * @covers MonteCarlo::getPid
     */
    public function testGetPid()
    {
        $this->assertEquals($this->object->getPid(), PID_CURR);
    }

    /**
     * @covers MonteCarlo::setParentPid
     */
    public function testSetParentPid()
    {
        $this->assertFalse($this->object->setParentPid(PID_NEW));
    }

    /**
     * @covers MonteCarlo::setParentPid
     * @expectedException InvalidArgumentException
     */
    public function testSetParentPidWrongType()
    {
        $this->object->setParentPid('WRONG PID TYPE');
    }

    /**
     * @covers MonteCarlo::getParentPid
     */
    public function testGetParentPid()
    {
        $this->assertEquals($this->object->getParentPid(), PID_PARENT);
    }
}
