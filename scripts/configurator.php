<?php
require_once 'Lib/ScriptFunctions.php';

// Some vars
$sqlRootPath = dirname ( dirname(__FILE__) );
$projectRootPath = dirname ( $sqlRootPath );
$projectName = end(explode("/", $projectRootPath)); // The project's root folder as a possible project name
$configFilePath =  $sqlRootPath . "/App/config.ini.default";
$finalConfigFilePath = $sqlRootPath . "/App/config.ini";

// HOOKS

// Hook always
$preCommitHookPlaceholder = $sqlRootPath . "/scripts/hooks/git-pre-commit";
$preCommitHook = $projectRootPath . "/.git/hooks/pre-commit";

$postMergeHookPlaceholder = $sqlRootPath . "/scripts/hooks/git-post-merge";
$postMergeHook = $projectRootPath . "/.git/hooks/post-merge";

// Hook just when adding --database to commit message. Ex: commit -m "added table --database"
$commitMsgPlaceholder = $sqlRootPath . "/scripts/hooks/git-suffix-commit-msg";
$commitMsgHook = $projectRootPath . "/.git/hooks/commit-msg";

$postCommitPlaceholder = $sqlRootPath . "/scripts/hooks/git-suffix-post-commit";
$postCommitHook = $projectRootPath . "/.git/hooks/post-commit";

$postMergeSuffixPlaceholder = $sqlRootPath . "/scripts/hooks/git-suffix-post-merge";
$postMergeSuffixHook = $projectRootPath . "/.git/hooks/post-merge";

if (!file_exists($configFilePath)) {
    ScriptFunctions::showMessageLine("Your config file .sql/App/config.ini.default doesn't exist");
    exit;
}

/** Messages **/
ScriptFunctions::underlineMessage("Welcome to MirenDB's configuration assistant",'-');
ScriptFunctions::showMessageLine("Remember you can set your configuration manually on .sql/App/config.ini.default and renaming it to config.ini");

// Steps
/** STEP 1: Target Database **/
ScriptFunctions::title("1. Credentials for local database");
$params['target_database'] = ScriptFunctions::getUserInputValueFor("Database Name",$projectName);
$params['target_host'] = ScriptFunctions::getUserInputValueFor("Host","localhost");
$params['target_port'] = ScriptFunctions::getUserInputValueFor("Port","3306");
$params['target_user'] = ScriptFunctions::getUserInputValueFor("User","root");
$params['target_password'] = ScriptFunctions::getUserInputValueFor("Password","");

/** STEP 1.1: Origin Database **/
ScriptFunctions::title("1.1 Revisions Database");
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

/** STEP 2: Target Database **/
ScriptFunctions::title("2. Credentials for repo database");
ScriptFunctions::showMessageLine("In case you're using an staging server and you want its DB to be automatically updated after pushing");
$params['use_staging_db'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want your staging DB to be automatically updated with revisions after pushing?","Y","Y/n"));
if ($params['use_staging_db']) {
    $params['staging_database'] = ScriptFunctions::getUserInputValueFor("Database Name",$projectName);
    $params['staging_host'] = ScriptFunctions::getUserInputValueFor("Host","localhost");
    $params['staging_port'] = ScriptFunctions::getUserInputValueFor("Port","3306");
    $params['staging_user'] = ScriptFunctions::getUserInputValueFor("User","root");
    $params['staging_password'] = ScriptFunctions::getUserInputValueFor("Password","");
}

/** STEP 3: Control Version **/
ScriptFunctions::title("3. Control Version");
$params['control_version'] = ScriptFunctions::returnOneOfTheOptionsOrDefault("git",array('git','svn'),ScriptFunctions::getUserInputValueFor("Which Control Version System are you using? - git/svn -","git"));
$params['export_hook'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to add a hook to automatically export revision when committing?","Y","Y/n"));
$params['import_hook'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to add a hook to automatically import revisions when pulling/updating?","Y","Y/n"));
$params['hooks_always'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to activate the hooks by default (Y) or to use the suffix --database in your commit messages (n) whenever you want the DB to be revisioned?","Y","Y/n"));

/** STEP 4: Behaviour **/
ScriptFunctions::title("4. Behaviour");
ScriptFunctions::showMessageLine("You can use MirenDB's standard methodology to export/import revisions or integrate it with another DB control versioning system");
$behaviour = ScriptFunctions::returnOneOfTheOptionsOrDefault("standard",array('standard','flyway','laravel','dbv'),ScriptFunctions::getUserInputValueFor("Which behaviour do you wanna use? - standard/flyway/laravel/dbv","standard"));
$params['behaviour'] = $behaviour . "_control_version";
$params['data'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to have data - apart from schemas - under version control?","Y","Y/n"));

/** STEP 5: Others - Environment, logging **/
ScriptFunctions::title("5. Others");
// XAMPP
$xampp = ScriptFunctions::trueOrFalseDefaultFalse(ScriptFunctions::getUserInputValueFor("Are you using XAMPP?","N","y/N"));
$suffix = ($xampp) ? "/opt/lampp/bin/" : "";
$params['php_path'] = $suffix . "php";
$params['mysql_path'] = $suffix . "mysql";
$params['mysqldump_path'] = $suffix . "mysqldump";

// Logs
$params['log'] = ScriptFunctions::trueOrFalseDefaultTrue(ScriptFunctions::getUserInputValueFor("Do you want to log on console a friendly resume of the revisions?","Y","Y/n"));

/** CREATE THE FINAL config.ini FILE */
$config = file_get_contents($configFilePath);
foreach ($params as $index=>$value) {

    // Tag on config.ini.default to be overwritten
    $tag = "**" . $index . "**";

    // Let's update the values
    $config = str_replace($tag,$value,$config);
}
file_put_contents($finalConfigFilePath,$config);


/** Let's add the HOOKS **/

if ($params['hooks_always']) {

    // Pre-commit hook (commit)
    if ($params['export_hook'] && $params['control_version']=="git") {

        // Let's change the tag **php_path**
        $gitPrecommitHook = file_get_contents($preCommitHookPlaceholder);
        $gitPrecommitHook = str_replace("**php_path**",$params['php_path'],$gitPrecommitHook);

        // If existing pre-commit hook
        // TODO: check if there's already a Hook of if Git is not installed
        file_put_contents($preCommitHook,$gitPrecommitHook);
        chmod($preCommitHook,0775);
    }

    // Post-merge Hook (pull)
    if ($params['import_hook'] && $params['control_version']=="git") {

        // Let's change the tag **php_path**
        $gitPostMergeHook = file_get_contents($postMergeHookPlaceholder);
        $gitPostMergeHook = str_replace("**php_path**",$params['php_path'],$gitPostMergeHook);

        // If existing pre-commit hook
        // TODO: check if there's already a Hook of if Git is not installed
        file_put_contents($postMergeHook,$gitPostMergeHook);
        chmod($postMergeHook,0775);
    }

} else {

    // Just (commit)
    if ($params['export_hook'] && $params['control_version']=="git") {

        // Commit-msg
        // Let's change the tag **php_path**
        $gitCommitMsgHook = file_get_contents($commitMsgPlaceholder);
        $gitCommitMsgHook = str_replace("**php_path**",$params['php_path'],$gitCommitMsgHook);

        // If existing pre-commit hook
        // TODO: check if there's already a Hook of if Git is not installed
        file_put_contents($commitMsgHook,$gitCommitMsgHook);
        chmod($commitMsgHook,0775);

        // Post-commit
        // Let's change the tag **php_path**
        $gitPostCommitHook = file_get_contents($postCommitPlaceholder);
        $gitPostCommitHook = str_replace("**php_path**",$params['php_path'],$gitPostCommitHook);

        // If existing pre-commit hook
        // TODO: check if there's already a Hook of if Git is not installed
        file_put_contents($postCommitHook,$gitPostCommitHook);
        chmod($postCommitHook,0775);

    }

    // Post-merge Hook (pull)
    if ($params['import_hook'] && $params['control_version']=="git") {

        // Let's change the tag **php_path**
        $gitPostMergeSuffixHook = file_get_contents($postMergeSuffixPlaceholder);
        $gitPostMergeSuffixHook = str_replace("**php_path**",$params['php_path'],$gitPostMergeSuffixHook);

        // If existing pre-commit hook
        // TODO: check if there's already a Hook of if Git is not installed
        file_put_contents($postMergeSuffixHook,$gitPostMergeSuffixHook);
        chmod($postMergeSuffixHook,0775);
    }


}

// TODO: If use_staging_db, add git hook, too




/** FINISH */
ScriptFunctions::title("FINISHED!");
ScriptFunctions::showMessageLine("Your configuration file has been successfully created on .sql/App/config.ini");
ScriptFunctions::showMessageLine("If you selected to add export/import hooks your database is now under version control");
ScriptFunctions::showMessageLine("-- Please remember to configure your .sqlignore file if you want some tables (or their data) not to be under version control --");
ScriptFunctions::writeBreakLine();
ScriptFunctions::showMessageLine("Cheers!");
ScriptFunctions::writeBreakLine();