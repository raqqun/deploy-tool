<?php
# import the paths for global use
include 'app_paths.php';
# import view class
include APP_DIR.'/tb_deploy_tool_view.php';


echo $view->render('header');

echo $view->render('gitreports');

echo $view->render('gitlog');

echo $view->render('footer');
