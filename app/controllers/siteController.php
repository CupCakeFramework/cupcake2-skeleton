<?php

namespace App\Controllers;

use CupCake2\Core\Nucleo;

class SiteController extends Nucleo {

    public $template = 'template_padrao';
    
    public function control_home(){
        $this->renderizar('home');
    }

}
