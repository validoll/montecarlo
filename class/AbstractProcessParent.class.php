<?php

/**
 * File contain abstract parent process class.
 * 
 * @author Vyacheslav Malchik <validoll-ru@yandex.ru>
 */

include_once 'AbstractProcess.class.php';

/**
 * Interface for parent process class
 */
interface InterfaceProcessParent {
    /**
     * Start parent process.
     * 
     */
    public function start();

    /**
     * Stop parent process.
     */
    public function stop();

    /**
     * Catch the signal from the child process.
     */
    public function recieveChildSignal();

    /**
     * Check child process status.
     */
    public function toLookAfterTheChilds();
}

/**
 * Abstract parent process class
 */
abstract class AbstractProcessParent extends AbstractProcess implements InterfaceProcessParent {

    /**
     * Stored max number of child process.
     * 
     * @var array
     */
    protected $maxChildProcesses;

    /**
     * Stored child process statuses.
     * 
     * @var array
     */
    protected $childProcesses;

    /**
     * Child process class name.
     * 
     * @var string
     */
    protected $childClassName;

    /**
     * Stored parent process stop semaphore.
     * 
     * @var bool
     */
    protected $stopProcess = FALSE;
    
    /**
     *  Stored parent process start time
     * 
     * @var int
     */
    protected $startTime;

    /**
     * Stored "task" objects.
     * 
     * @var array
     */
    protected $task;

    /**
     * Start process on class constructor.
     * 
     * @param string $childClassName
     *   Child process class name.
     * @param int $max_childs
     *   Max number of child process.
     */
    public function __construct($childClassName, $max_childs = 2) {
        pcntl_sigprocmask(SIG_BLOCK, array(SIGUSR1, SIGUSR2, SIGCHLD));

        $this->startTime = self::getTime();
        $this->maxChildProcesses = $max_childs;
        self::isChildClass($childClassName);
        $this->childClassName = $childClassName;
        $this->start();
    }
    
    /**
     * Get start time.
     * 
     * @return float
     *  Start time
     */
    public function getStartTime() {
        return $this->startTime;
    }
    
    /**
     * Helper function for get current time.
     * 
     * @return float
     *  Current time.
     */
    static function getTime() {
	$mtime = microtime();
	$mtime = explode(' ', $mtime);
	$mtime = $mtime[1] + $mtime[0];
	return $mtime;
    }

    /**
     * Check whether a class is a child process.
     * 
     * @param mixed $class
     *   Class or class name.
     * @return boolean
     */
    static function isChildClass($class) {
        $childClassParents = class_parents($class, true);
        if (in_array('AbstractProcessChild', $childClassParents)) {
            return TRUE;
        } else 
        {
            $error = "Received no child process class";
            throw new Exception($error);
        }
    }
    /**
     * Return execution time of sctipt in seconds.
     * 
     * @return float
     */
    public function timer() {
        return self::getTime() - $this->startTime;
    }

    /**
     * Stop parent process.
     */
    public function stop() {
        $this->stopProcess = TRUE;
    }

    /**
     * Start parent process.
     * 
     * @param string $childClassName
     *   Child process class name.
     */
    public function start() {
        $this->childProcesses = array();
        $this->setPid(posix_getpid());
        for ($proc_num = 0; $proc_num < $this->maxChildProcesses; $proc_num++) {
            $pid = pcntl_fork();
            if ($pid == 0) break;
            $this->childProcesses[$pid] = true;
        }

        while (!$this->stopProcess) {
            if (count($this->childProcesses) < $this->maxChildProcesses) {
                if ($pid == -1) {
                        // Process was not created
                    die('Something is wrong :(');
                } elseif ($pid) {
                    // Process was created
                } else {
                    $pid = getmypid();
                    $this->task[$pid] = new $this->childClassName($pid, $this->pid);
                    // Prepare child process
                    $this->prepareChild($pid);
                    // Run child process
                    $this->task[$pid]->execute();
                    // Final actions
                    $this->childFinalize($pid);
                }
            } else {
                $this->parentAction();
            }
            // Chech child processes
            $this->toLookAfterTheChilds();
        }
    }

    /**
     * Catch the signal from the child process.
     * 
     * @return
     *  Returns the <b>PID</b> when recieved the signal from the child 
     *  process, otherwise it returns <b>NULL</b>.
     */
    public function recieveChildSignal() {
        $pid = NULL;
        $siginfo = $this->recieveSignal(SIGCHLD);
        if (isset($siginfo['pid'])) {
            $pid = $siginfo['pid'];
        }
        return $pid;
    }

    /**
     * Check child process status.
     */
    public function toLookAfterTheChilds() {
        while ($signaled_pid = pcntl_waitpid(-1, $status, WNOHANG)) {
            if ($signaled_pid == -1) {
                // No one child
                $this->childProcesses = array();
                exit(0);
                break;
            } else {
                // Child is finalized
                unset($this->childProcesses[$signaled_pid]);
            }
        }
    }

    /**
     * Listen signals from child process.
     */
    abstract public function listenChilds();

    /**
     * Hook for prepare child process object.
     * 
     * @param int $pid
     *  Child process identifier.
     */
    abstract public function prepareChild($pid);

    /**
     * Final actions for child process.
     * 
     * @param int $pid
     *  Child process identifier.
     */
    abstract public function childFinalize($pid);

    /**
     * Actions in parent process.
     */
    abstract public function parentAction();
}
