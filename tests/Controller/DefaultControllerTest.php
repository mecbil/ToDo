<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\request;

class DefaultControllerTest extends WebTestCase
{
    public function testDisplayHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        // $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo');
        // $this->assertResponseRedirects('/login');

    }
}
