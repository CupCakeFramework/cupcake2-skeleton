<?php

use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class InstallController {

    public function control_install($id) {
        $loader = new Loader();
        $loader->loadFromDirectory('/config/fixtures');
        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }

}
