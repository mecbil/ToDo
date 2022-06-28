<?php

namespace App\Tests\Form;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserFormTest extends WebTestCase
{
    public function connect()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Deschamps';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);

        return $client;
    }

    // Tester la page Users sans etre connecter
    public function testDisplayTaskPageUsersNotConnected(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testDisplayUserPageConnected(): void
    {
        $client = $this->connect();
               
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List,");
        
        $client->request('GET', '/users');
        // $crawler->selectLink('Lister les utilisateurs')->link();

        // $this->assertResponseRedirects();
        // $client->followRedirect();

        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    // public function testDisplayTaskPageConnected(): void
    // {
    //     $client = $this->connect();

    //     $this->assertResponseRedirects();
    //     $client->followRedirect();

    //     $crawler = $client->request('GET', '/users');
    //     $this->assertSelectorTextContains('label', 'Title');

    //     $form = $crawler->selectButton('Ajouter')->form();
    //     $form["task[title]"] = 'title';
    //     $form["task[content]"] = 'content';

    //     $client->submit($form);

    //     $this->assertResponseRedirects();
    //     $client->followRedirect();
    //     $this->assertSelectorExists('.alert.alert-success');

    // }
}
