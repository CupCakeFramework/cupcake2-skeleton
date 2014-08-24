<?php

namespace App;

class Module {

    public function getConfig() {
        return include(__DIR__ . '/../config/app.config.php');
    }

}
