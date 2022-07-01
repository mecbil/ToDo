<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    //se connecter 
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

    //Tester la page Tasks
    public function testDisplayTaskspage()
    {
        //Se connecter
        $client = $this->connect();
        //Suivre la redirection
        $this->assertResponseRedirects();
        $client->followRedirect();   

        //Aller à l'adresse /tasks 
        $crawler = $client->request('GET', '/tasks');
        //tester
        $this->assertSelectorTextContains('p', "Créer une tâche");
        // $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List,");

    }

    public function testTaskCreate()
    {
        //Se connecter
        $client = $this->connect();
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
        $client = $this->connect();

        //Suivre la redirection
        $this->assertResponseRedirects();
        $client->followRedirect();   

        //Aller à l'adresse /tasks 
        $crawler = $client->request('GET', '/tasks/done');
        //tester
        $this->assertSelectorTextContains('p', "Créer une tâche");
    }

    public function testAddEditDeleteTask(): void
    {
        $client = $this->connect();
        //Add user
        $this->assertResponseRedirects();
        $client->followRedirect();

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertSelectorTextContains('label', 'Title');

        $form = $crawler->selectButton('Ajouter')->form();
        $form["task[title]"] = 'testTitle';
        $form["task[content]"] = 'TestContent';


        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');

        //Recuperer le LastId
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
           
        $user = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['title' => 'testTitle'])
        ;
        $id = $user->getId();
        
        //Editer le Task
        $crawler = $client->request('GET', '/tasks/'.$id.'/edit');
        $this->assertSelectorTextContains('button', 'Modifier');

        $form = $crawler->selectButton('Modifier')->form();
        $form["task[title]"] = 'testTitle2';
        $form["task[content]"] = 'TestContent2';
        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');

        // Supprimer le User
        $crawler = $client->request('GET', '/tasks/'.$id.'/delete');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

}