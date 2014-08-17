<?php

namespace Config\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use App\Models\UsuarioAdmin;

class loadAdminUsuarioData implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $usuario = new UsuarioAdmin();
        $usuario->setLogin('admin');
        $usuario->setSenha('123456');
        $manager->persist($usuario);
        $manager->flush();
    }

}
