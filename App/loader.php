<?php

// Class Loading
function classAutoLoader($class) {
    $includePaths = array(
        ROOT_PATH. 'App/',
        ROOT_PATH. 'App/Models/',
        ROOT_PATH. 'App/Models/Comparators/',
        ROOT_PATH. 'App/Models/Comparators/DataComparators/',
        ROOT_PATH. 'App/Models/QueryGenerators/',
        ROOT_PATH. 'App/Lib/',
        ROOT_PATH. 'App/Behaviours/'
    );
    set_include_path(implode(':',$includePaths));
    require $class . ".php";
}
spl_autoload_register('classAutoLoader');