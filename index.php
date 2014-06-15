<?php

/**
 * Main script.
 * 
 * @author Vyacheslav Malchik <validoll-ru@yandex.ru>
 */

/**
 * Max number of child processes.
 */
define('MAX_CHILD_PROCESSES', 5);

/**
 * Class name of child process.
 */
define('CHILD_PROCESS_CLASS', 'MonteCarlo');

/**
 * Attempt to load undefined class.
 * 
 * @param string $classname
 *   Name of the class to load.
 */
function __autoload($classname) {
    $filename = 'class/' . $classname . '.class.php';
    include_once ($filename);
}

$main = new Main(CHILD_PROCESS_CLASS, MAX_CHILD_PROCESSES);

$main->start();
