<?php include 'config/tb_deploy_tool_view.php';


echo $view->render('header');
error_log('ok');
echo $view->render('gitreports');

echo $view->render('gitlog');

echo $view->render('footer');
