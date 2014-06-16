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
     * Set identifier of parent process.
     * @param int $pid
     */
    public function setParentPid($pid) {
        // Check pid type
        self::checkPid($pid);
        // Check that PID was set
        if (!$this->getParentPid()) {
            return $this->parentPid = $pid;
        } else
        {
            return FALSE;
        }
    }

    /**
     * Return parent pid
     * @return int
     */
    public function getParentPid() {
        if (!empty($this->parentPid)) {
            return $this->parentPid;
        } else
        {
            return FALSE;
        }
    }

    /**
     * Set pid's on class constructor.
     * 
     * @param int $pid
     *  pid of current process
     * @param int $parent_pid
     *  pid of parent process
     */
    public function __construct($pid, $parent_pid) {
        $this->setPid($pid);
        $this->setParentPid($parent_pid);
    }
}
