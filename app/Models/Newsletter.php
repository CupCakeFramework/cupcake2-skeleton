<?php

/**
 * @Entity @Table(name="cup_newsletter")
 * */
class Newsletter {

    /** @Id @Column(type="integer") @GeneratedValue * */
    protected $id;

    /** @Column(type="string") * */
    protected $nome;

    /** @Column(type="string") * */
    protected $email;

}
