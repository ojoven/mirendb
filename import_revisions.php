<?php

// Defines
define('ROOT_PATH', __DIR__ . "/");

require_once 'App/loader.php';

// Let's create the App
$app = new App();

try {
    $start = microtime(true);

    $importEnv = (isset($argv[1])) ? $argv[1] : "local"; // local or staging, we'll use different credentials

    // Import revisions. Go go go!
    $app->importEnv = $importEnv;
    $app->import();

    // Measure script time
    $timeElapsed = microtime(true) - $start;
    $app->log(round($timeElapsed,2) . "s elapsed");

} catch (Exception $e) {

    echo $e->getMessage() . PHP_EOL;

}




