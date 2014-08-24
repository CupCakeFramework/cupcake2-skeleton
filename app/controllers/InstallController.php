<?php

namespace App\Controllers;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use CupCake2\Core\CupApp;

class InstallController extends CupApp {

    public function control_home($id) {
        if ($id == $this->configManager->getEnvironmentConfigFromKey('APP_INSTALL_SECRET')) {
            $this->run();
        } else {
            $this->request->erro_404();
        }
    }

    public function run() {
        $this->loadDatabase();
        $this->runFixtures();
    }

    public function loadDatabase() {
        header('Content-Type: text/html; charset=utf-8');
        $this->dbg('Criando o schema');
        try {
            $tool = new SchemaTool($this->db->getEntityManager());
            $entities = array();
            $meta = $this->db->getEntityManager()->getMetadataFactory()->getAllMetadata();
            foreach ($meta as $m) {
                $entities[] = $this->db->getEntityManager()->getClassMetadata($m->getName());
            }
            $tool->createSchema($entities);
        } catch (Exception $ex) {
            $this->dbg('Ocorreu o seguinte erro na criação do schema : ' . $ex);
        }
        $this->dbg('Criação do schema finalizada');
    }

    public function runFixtures() {
        $this->dbg('Iniciando as fixtures');
        $fixturesList = $this->configManager->getConfigFromKey('fixtures_dir');
        try {
            $this->dbg('Executando as fixtures do diretorio : ' . $fixturePath);
            $loader = new Loader();
            foreach ($fixturesList as $fixturePath) {
                $this->dbg('Fixtures deste diretorio:');
                $this->dbg(scandir($fixturePath));
                $loader->loadFromDirectory($fixturePath);
            }
            $purger = new ORMPurger();
            $executor = new ORMExecutor($this->db->getEntityManager(), $purger);
            $executor->execute($loader->getFixtures());
        } catch (Exception $ex) {
            $this->dbg('Ocorreu o seguinte erro na inserção das fixtures : ' . $ex . ' no diretorio > ' . $fixturePath);
        }
        $this->dbg('Finalizado as inserções de fixtures');
    }

}
