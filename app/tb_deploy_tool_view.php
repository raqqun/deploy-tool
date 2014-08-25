<?php
require_once APP_DIR.'tb_deploy_tool_controller.php';

class View {

    protected $template = null;
    public $controller = null;

    public function __construct() {
        $this->controller = new Controller();
    }

    public function render($template) {
        $this->template = $template;

        ob_start();
        include(STATIC_DIR . $this->template . '.php');
        $template = ob_get_clean();

        return $template;
    }
}

$view = new View();
