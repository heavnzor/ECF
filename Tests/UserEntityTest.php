<?php

namespace Tests\AppBundle\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;


class UserTest extends TestCase
{
    // …

    public function testNewUser()
    {
        $user = new User();
        $user->setEmail("hpg@brazzers.com");
        $user->setPassword('eEkdkSLKLZRJd430DSD04ds');
        $user->setPseudo("hervé");
        $this->assertSame("hpg@brazzers.com", $user->getEmail());
    }
}