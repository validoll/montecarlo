<?php

/**
 * File contain abstract child process class.
 * 
 * @author Vyacheslav Malchik <validoll-ru@yandex.ru>
 */

include_once 'AbstractProcess.class.php';

/**
 * Interface for child process class
 */
interface InterfaceProcessChild {
    
    /**
     * Execution of child process.
     */
    public function execute();

    /**
     * Return results of execution of child process.
     */
    public function getResult();
}

/**
 * Abstract child process class
 */
abstract class AbstractProcessChild extends AbstractProcess implements InterfaceProcessChild {

    /**
     * Store parent pid
     * 
     * @var int
     */
    public $parentPid;

    /**
     * Set pid's on class constructor.
     * 
     * @param int $pid
     *  pid of current process
     * @param int $parent_pid
     *  pid of parent process
     */
    public function __construct($pid, $parent_pid) {
        $this->pid = $pid;
        $this->parentPid = $parent_pid;
    }

    /**
     * Return parent pid
     * @return int
     */
    public function getParentPid() {
        return $this->parentPid;
    }
}
