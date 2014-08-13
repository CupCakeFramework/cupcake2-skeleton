<?php

namespace App\Controllers;

use CupCake2\Core\CupCore;

class SiteController extends CupCore {

    public $template = 'template_padrao';

    public function control_home() {
        $this->renderizar('home');
    }

    public function control_teste_lelek(){
        $this->renderizar('home');
    }
    
}
