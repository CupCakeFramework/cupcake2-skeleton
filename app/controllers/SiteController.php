<?php

namespace App\Controllers;

use CupCake2\Core\CupApp;

class SiteController extends CupApp {

    public function control_home() {
        $this->renderizar('site/home');
    }

    public function control_teste_lelek() {
        $this->renderizar('home');
    }

}
