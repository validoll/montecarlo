<?php

/**
 * File contain implementation of parent process class.
 * 
 * @author Vyacheslav Malchik <validoll-ru@yandex.ru>
 */

include_once 'AbstractProcessParent.class.php';

/**
 * Main process class.
 */
class Main extends AbstractProcessParent {

    /**
     * Listen signals from child process.
     * If a signal is received, the display information messages, 
     * and send a signal to the child process, 
     * from which the signal was received.
     */
    public function listenChilds() {
        if ($pid = $this->recieveChildSignal()) {
            echo 'Signal recieved from pid ' . $pid . '. Uptime ';
            printf("%0.2fs.\n", $this->timer());
            $this->sendSignal($pid, SIGUSR1);
        }
    }

    /**
     * Hook for prepare child process object.
     * Set number of iterations.
     */
    public function prepareChild($pid) {
        $n = rand(50000, 100000);
        $this->task[$pid]->setIters($n);
    }

    /**
     * Final actions for child process.
     * Print result and exit.
     */
    public function childFinalize($pid) {
        echo "======================================\n";
        echo "\nFinal result:\n";
        echo $this->task[$pid];
        printf("Execution time: %0.2fs.\n", $this->timer());
        echo "======================================\n";
        exit(0);
    }

    /**
     * Actions in parent process.
     * Listen child processes and answer on signals.
     */
    public function parentAction() {
        $this->listenChilds();
    }
}
