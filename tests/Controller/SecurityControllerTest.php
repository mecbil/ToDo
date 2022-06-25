<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLoginpage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('label', "Nom d'utilisateur");

    }

    public function testLetLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Delaunay';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();       
        $this->assertSelectorNotExists('.alert.alert-danger');
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List,");
    }

    public function testLogonWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Coucou';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);
        
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }
}
