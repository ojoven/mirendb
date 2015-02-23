<?php
require_once 'Lib/ScriptFunctions.php';

// Some vars
$sqlRootPath = dirname ( dirname(__FILE__) );
$projectRootPath = dirname ( $sqlRootPath );
$projectName = end(explode("/", $projectRootPath)); // The project's root folder as a possible project name
$configFilePath =  $sqlRootPath . "/App/config.ini.default";
$finalConfigFilePath = $sqlRootPath . "/App/config.ini";
$preCommitHookPlaceholder = $sqlRootPath . "/scripts/hooks/git-pre-commit";
$preCommitHook = $projectRootPath . "/.git/hooks/pre-commit";
if (!file_exists($configFilePath)) {
    ScriptFunctions::showMessageLine("Your config file .sql/App/config.ini.default doesn't exist");
    exit;
}

/** Messages **/
ScriptFunctions::underlineMessage("Welcome to the Sql Diff Generator configuration assistant",'-');
ScriptFunctions::showMessageLine("Remember you can set your configuration manually on .sql/App/config.ini.default and renaming it to config.ini");

// Steps
/** STEP 1: Target Database **/
ScriptFunctions::title("1. Database configuration");
$params['target_database'] = ScriptFunctions::getUserInputValueFor("Database Name",$projectName);
$params['target_host'] = ScriptFunctions::getUserInputValueFor("Host","localhost");
$params['target_port'] = ScriptFunctions::getUserInputValueFor("Port","3306");
$params['target_user'] = ScriptFunctions::getUserInputValueFor("User","root");
$params['target_password'] = ScriptFunctions::getUserInputValueFor("Password","");

/** STEP 2: Origin Database **/
ScriptFunctions::title("2. Revisions Database");
ScriptFunctions::showMessageLine("We'll need to create an auxiliary database where to import the revisions and to compare it to the database under version control");
$auxiliaryDBName = $params['target_database'] . "_revisions";
$params['origin_database']= ScriptFunctions::getUserInputValueFor("Auxiliary Database Name",$auxiliaryDBName);
$cloneMySQLCredentials = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to reuse the previous credentials - " . $params['target_host'] . ", " . $params['target_port'] . ", " . $params['target_user'] . "...?","Y","Y/n"));
if ($cloneMySQLCredentials) {
    $params['origin_host'] = $params['target_host'];
    $params['origin_port'] = $params['target_port'];
    $params['origin_user'] = $params['target_user'];
    $params['origin_password'] = $params['target_password'];
} else {
    $params['origin_host'] = ScriptFunctions::getUserInputValueFor("Host","localhost");
    $params['origin_port'] = ScriptFunctions::getUserInputValueFor("Port","3306");
    $params['origin_user'] = ScriptFunctions::getUserInputValueFor("User","root");
    $params['origin_password'] = ScriptFunctions::getUserInputValueFor("Password","");
}

/** STEP 3: Control Version **/
ScriptFunctions::title("3. Control Version");
$params['control_version'] = ScriptFunctions::returnOneOfTheOptionsOrDefault("git",array('git','svn','mercurial'),ScriptFunctions::getUserInputValueFor("Which Control Version System are you using? - git/svn/mercurial -","git"));
$params['export_hook'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to add a hook to export revision when commit?","Y","Y/n"));
$params['import_hook'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to add a hook to import revision when pulling/updating?","Y","Y/n"));

/** STEP 4: Behaviour **/
ScriptFunctions::title("4. Behaviour");
ScriptFunctions::showMessageLine("You can use the Sql Diff Generator's standard methodology to export/import revisions or integrate it with another DB control versioning system");
$behaviour = ScriptFunctions::returnOneOfTheOptionsOrDefault("standard",array('standard','flyway','laravel','dbv'),ScriptFunctions::getUserInputValueFor("Which behaviour do you wanna use? - standard/flyway/laravel/dbv","standard"));
$params['behaviour'] = $behaviour . "_control_version";
$params['data'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to have data - apart from schemas - under version control?","Y","Y/n"));

/** STEP 5: Environment **/
ScriptFunctions::title("5. Environment");
$xampp = ScriptFunctions::trueOrFalseDefaultFalse(ScriptFunctions::getUserInputValueFor("Are you using XAMPP?","N","y/N"));
$suffix = ($xampp) ? "/opt/lampp/bin/" : "";
$params['php_path'] = $suffix . "php";
$params['mysql_path'] = $suffix . "mysql";
$params['mysqldump_path'] = $suffix . "mysqldump";


/** CREATE THE FINAL config.ini FILE */
$config = file_get_contents($configFilePath);
foreach ($params as $index=>$value) {

    // Tag on config.ini.default to be overwritten
    $tag = "**" . $index . "**";

    // Let's update the values
    $config = str_replace($tag,$value,$config);
}
file_put_contents($finalConfigFilePath,$config);


/** Let's add the HOOKS, too **/
// Pre-commit hook
if ($params['export_hook'] && $params['control_version']=="git") {

    $gitPrecommitHook = file_get_contents($preCommitHookPlaceholder);

    // Let's change the tag **php_path**
    $gitPrecommitHook = str_replace("**php_path**",$params['php_path'],$gitPrecommitHook);

    // If existing pre-commit hook
    // TODO: check if there's already a Hook of ir Git is not installed
    file_put_contents($preCommitHook,$gitPrecommitHook);
}




/** FINISH */
ScriptFunctions::title("FINISHED!");
ScriptFunctions::showMessageLine("Your configuration file has been successfully created on .sql/App/config.ini");
ScriptFunctions::showMessageLine("If you selected to add export/import hooks your database is now under version control");
ScriptFunctions::showMessageLine("-- Please remember to configure your .sqlignore file if you want some tables (or their data) not to be under version control --");
ScriptFunctions::writeBreakLine();
ScriptFunctions::showMessageLine("Cheers!");
ScriptFunctions::writeBreakLine();