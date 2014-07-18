<?php
require_once 'tb_deploy_tool_controller.php';

class View {

    protected $template = null;
    public $controller;

    public function __construct() {
        $this->controller = new Controller();
    }

    public function render($template) {
        $this->template = $template;

        ob_start();
        include('static/' . $this->template . '.php');
        $view = ob_get_clean();

        return $view;
    }
}

$view = new View();
