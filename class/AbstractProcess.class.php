<?php

/**
 * File contain abstract process class.
 * 
 * @author Vyacheslav Malchik <validoll-ru@yandex.ru>
 */

/**
 * Interface for process class
 */
interface InterfaceProcess {

    /**
     * Send signal to process.
     * 
     * @param int $pid
     *  Process identifier.
     * @param int $signal
     *  Signal constant.
     */
    public function sendSignal($pid, $signal);

    /**
     * Recieve signal.
     * 
     * @param int $signal
     *  Signal constant.
     * @return
     *  Returns the array with signal info when recieved the signal, 
     *  otherwise it returns <b>NULL</b>.
     */
    public function recieveSignal($signal);
}

/**
 * Abstract process class
 */
abstract class AbstractProcess implements InterfaceProcess {

    /**
     * Store pid of current process.
     * 
     * @var int
     */
    public $pid;

    /**
     * Set identifier of current process.
     * @param int $pid
     */
    public function setPid($pid) {
        $this->pid = $pid;
    }

    /**
     * Return identifier of current process.
     * @return int
     */
    public function getPid() {
        return $this->pid;
    }

    /**
     * Send signal to process.
     * 
     * @param int $pid
     *  Process identifier.
     * @param int $signal
     *  Signal constant.
     */
    public function sendSignal($pid, $signal) {
        posix_kill($pid, $signal);
    }

    /**
     * Recieve signal.
     * 
     * @param int $signal
     *  Signal constant.
     * @return
     *  Returns the array with signal info when recieved the signal, 
     *  otherwise it returns <b>NULL</b>.
     */
    public function recieveSignal($signal) {
        $siginfo = NULL;
        pcntl_sigtimedwait(array($signal), $siginfo, 0);
        return $siginfo;
    }
}