<?php

/**
 * File contain implementation of child process class.
 * 
 * @author Vyacheslav Malchik <validoll-ru@yandex.ru>
 */

include_once 'AbstractProcessChild.class.php';

/**
 * Class for calculating Pi by Monte Carlo method.
 */
class MonteCarlo extends AbstractProcessChild {

    /**
     * Store result of execution.
     * 
     * @var int
     */
    protected $result;

    /**
     * Store totla number of iterations.
     * 
     * @var int
     */
    protected $iters;

    /**
     * Store current step.
     * 
     * @var int
     */
    protected $step;


    public function __toString() {
        return "    Process: $this->pid\n"
              ."    Pi = $this->result\n"
              ."    Step  $this->step of $this->iters\n\n";
    }

    /**
     * Getter for result.
     * 
     * @return
     *  Result of execution.
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * Set number of iterations.
     * 
     * @param int $n
     *  Number of iterations.
     */
    public function setIters($n) {
        $this->iters = $n;
    }

    /**
     * Get number of iterations.
     * 
     * @return int
     *  Number of iterations.
     */
    public function getIters() {
        return $this->iters;
    }

    /**
     * Returns a random value between 0 and 1.
     * 
     * @return float
     *  Random value.
     */
    static function random() {
        return (float)rand()/(float)getrandmax();
    }
    
    /**
     * Get a random power of 10.
     * 
     * @return int
     */
    static function getNumber() {
        $pre = rand(1000,150000);
        return pow(10, strlen($pre)-1);
    }
    
    /**
     * Catches a signal of parent process and respond to it.
     */
    public function autoAnswer() {
        $pid = $this->recieveSignal(SIGUSR1);
        if ($pid) {
            echo 'Signal recieved from parent pid ' 
                . $this->parentPid 
                . ". Print current result:\n";
            echo $this;
        }
    }

    /**
     * Calculating Pi by Monte Carlo method.
     */
    public function execute() {
        $np = 0;
        $n = $this->iters;
        for ($i = 1; $i <= $n; $i++) {

          $x = self::random() * 2 - 1;
          $y = self::random() * 2 - 1;
          if($x * $x  + $y * $y <= 1) {
            $np++;
          }

          // Save the current values
          $this->step = $i;
          $this->result = 4 * $np / $i;
          
          // Send a signal to the parent process on a random condition
          if ($i % self::getNumber() == 0) {
              $this->sendSignal($this->parentPid, SIGCHLD);
          }
          // Answer, if the signal obtained from the parent process
          $this->autoAnswer();
        }
        // The final value
        $this->result = 4 * $np / $n;  
    }
}
