<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\request;

class DefaultControllerTest extends WebTestCase
{
    public function testNoDisplayHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
       
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

    }

    public function testDisplayHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Deschamps';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);

        //Suivre la redirection
        $this->assertResponseRedirects();
        $client->followRedirect();  

        $crawler = $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo');

    }
}
