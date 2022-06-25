<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\form;
use Symfony\Component\DependencyInjection\Loader\Configurator\request;

class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLoginpage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label', "Nom d'utilisateur");

    }

    public function testLetLoggedIn()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Hoareau';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);
        
        $crawler = $client->request('GET', '/login');

        // $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List,");
        
        $this->assertSelectorExists('.alert.alert-danger');
        // $this->assertResponseStatusCodeSame(Response::HTTP_Redi);
        // $this->assertResponseRedirects('/');
        // $client->followRedirect();
        

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
