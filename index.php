<?php

// Defines
define('ROOT_PATH', __DIR__ . "/");
define('BEHAVIOUR', 'dbvControlVersion'); // dbvControlVersion, bothDatabase, bothFile

require_once 'App/loader.php';

// Let's create the App
$app = new App();

try {
    $start = microtime(true);

    // Use a behaviour
    $behaviour = BehaviourFactory::getBehaviour(BEHAVIOUR);
    // And run!
    $app->run($behaviour);

    // Measure script time
    $timeElapsed = microtime(true) - $start;
    echo $timeElapsed . " time elapsed" . PHP_EOL;

} catch (Exception $e) {

    echo $e->getMessage() . PHP_EOL;

}




