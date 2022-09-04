<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Waldganger")');
    }
    public function testDons()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink("Don");
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('div:contains("Don")');
    }
    public function testLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $client->submitForm('Connexion', [
            'email' => 'user@waldganger.net',
            'password' => 'ecfjuin2022'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('div:contains("Déconnexion")');
    }
}