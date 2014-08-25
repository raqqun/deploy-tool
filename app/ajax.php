<?php
require_once '../app_paths.php';
require_once APP_DIR.'/tb_deploy_tool_controller.php';

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
    case 'import':
        echo $controller->synchronizeProdWithPreprod();
        break;
}
