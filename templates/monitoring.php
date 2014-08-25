<?php
require_once '../app_paths.php';
include APP_DIR.'/tb_deploy_tool_view.php'; ?>

<?php echo $view->render('/header'); ?>

<div id="actions">
    <div class="container">
        <button type="button" data-action="import" class="action btn btn-lg btn-primary btn-block"><span class"glyphicon glyphicon-transfer"></span>Import Database</button>
    </div>
</div>

<?php echo $view->render('/footer'); ?>
