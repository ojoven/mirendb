<?php

// Defines
define('ROOT_PATH', __DIR__ . "/");

require_once 'App/loader.php';

// Let's create the App
$app = new App();

try {
    $start = microtime(true);

    // Run!
    $app->run();

    // Measure script time
    $timeElapsed = microtime(true) - $start;
    echo $timeElapsed . " time elapsed" . PHP_EOL;

} catch (Exception $e) {

    echo $e->getMessage() . PHP_EOL;

}




