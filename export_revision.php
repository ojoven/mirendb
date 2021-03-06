<?php

// Defines
define('ROOT_PATH', __DIR__ . "/");

require_once 'App/loader.php';

// Let's create the App
$app = new App();

try {
    $start = microtime(true);

    // Export revision. Go go go!
    $app->export();

    // Measure script time
    $timeElapsed = microtime(true) - $start;
    $app->log(round($timeElapsed,2) . "s elapsed");

} catch (Exception $e) {

    echo $e->getMessage() . PHP_EOL;

}




