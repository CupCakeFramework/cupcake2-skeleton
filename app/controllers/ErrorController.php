<?php

namespace App\Controllers;

use CupCake2\Core\CupApp;

/**
 * @author Ricardo Fiorani
 */
class ErrorController extends CupApp {

    public function control_404() {
        $this->renderizar('error/404');
    }

}
