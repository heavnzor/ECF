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
        $this->assertSelectorTextContains('a', 'Greenpeace');
    }
    public function testOrganisation()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $client->clickLink("L'organisation");
        $this->assertResponseIsSuccessful();
    }
}