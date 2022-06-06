<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('html:contains("Waldganger")')->count());
    }
    public function testDons()
    {
        $client = static::createClient();
        $crawler =  $client->request('GET', '/');
        $client->clickLink("Don");
        $this->assertResponseIsSuccessful();
        $this->assertSame(1, $crawler->filter('html:contains("Don")')->count());
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