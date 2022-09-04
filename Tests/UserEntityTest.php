<?php

namespace Tests\AppBundle\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;


class UserTest extends TestCase
{
    public function testEmailUser()
    {
        $user = new User();
        $user->setEmail("herve@waldganger.com");
        $this->assertSame("herve@waldganger.net", $user->getEmail(), "l'email n'est pas celui attendu");
    }
}