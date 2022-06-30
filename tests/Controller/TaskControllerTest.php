<?php

namespace App\Tests\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    //Tester la page Tasks
    public function testDisplayTaskspage()
    {
        //Se connecter
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        // Entrer les identifiants
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Delaunay';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);
        //Suivre la redirection
        $this->assertResponseRedirects('/');
        $client->followRedirect();   

        //Aller à l'adresse /tasks 
        $crawler = $client->request('GET', '/tasks');
        //tester
        $this->assertSelectorTextContains('p', "Créer une tâche");
        // $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List,");

    }

    public function testDisplayTaskCreatepage()
    {
        //Se connecter
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        // Entrer les identifiants
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Delaunay';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);
        //Suivre la redirection
        $this->assertResponseRedirects();
        $client->followRedirect();   

        //Aller à l'adresse /tasks 
        $crawler = $client->request('GET', '/tasks/create');
        //tester
        $this->assertSelectorTextContains('label', "Title");

    }

    public function testDisplayTaskDonepage()
    {
        //Se connecter
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        // Entrer les identifiants
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Delaunay';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);
        //Suivre la redirection
        $this->assertResponseRedirects();
        $client->followRedirect();   

        //Aller à l'adresse /tasks 
        $crawler = $client->request('GET', '/tasks/done');
        //tester
        $this->assertSelectorTextContains('p', "Créer une tâche");

    }

}