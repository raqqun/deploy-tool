<?php
require_once '../app_paths.php';
include APP_DIR.'/tb_deploy_tool_view.php'; ?>

<?php echo $view->render('/header'); ?>

<div class="container">
    <div id="actions">
        <button type="button" data-action="import" class="action btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-transfer"></span>Import Database</button>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Latest Database Import Log</h3>
        </div>
    <div class="panel-body">
        <?php $log = $view->controller->printImportLatestDatabaseLog(); ?>
        <?php if(!empty($log)): ?>
            <?php foreach ($log as $line): ?>
                <p><?php echo $line; ?></p>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</div>
</div>

<?php echo $view->render('/footer'); ?>
