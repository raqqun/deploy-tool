<?php
require_once APP_DIR.'/tb_deploy_tool_controller.php';

$controller = new Controller();

// Get data-action and do the job
switch ($_REQUEST['action']) {
    case 'pull':
        echo $controller->gitPull();
        break;

    case 'deploy-dryrun':
        echo $controller->deployToProduction($dryRun = true);
        break;
    case 'deploy':
        echo $controller->deployToProduction($dryRun = false);
        break;
}
