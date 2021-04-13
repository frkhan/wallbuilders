<?php

// No direct access
if (!defined('ABSPATH')) {
    die('Error #845173685');
}

class GetInvited extends AddPipe {

    public function __construct() {
        parent::__construct();
        $this->loadView();
    }

    public function __toString() {
        return __CLASS__;
    }

    public function loadView() {
        require_once plugin_dir_path(__DIR__) . 'views/get_invited.php';
    }

}
