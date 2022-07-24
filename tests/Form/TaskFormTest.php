<?php

namespace App\Tests\Form;

use App\Entity\Task;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\request;

class TaskFormTest extends WebTestCase
{
    public function connect()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Delaunay';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);

        return $client;
    }
    
    public function testDisplayTaskPageNotConnected(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testDisplayTaskPageConnected(): void
    {
        $client = $this->connect();

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAddEditDeleteTask(): void
    {
        $client = $this->connect();
        //Add task
        $this->assertResponseRedirects();
        $client->followRedirect();

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertSelectorTextContains('label', "Title");

        $form = $crawler->selectButton('Ajouter')->form();
        $form["task[title]"] = 'TitleTest';
        $form["task[content]"] = 'ContentTast';

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');

        // Recuperer le LastId
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
           
        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['title' => 'TitleTest'])
        ;
        $id = $task->getId();
        
        // Editer le User
        $crawler = $client->request('GET', '/tasks/'.$id.'/edit');
        $this->assertSelectorTextContains('label', "Title");

        $form = $crawler->selectButton('Modifier')->form();
        $form["task[title]"] = 'TitleTest2';
        $form["task[content]"] = 'ContentTast2';
        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');

        // // Supprimer le Task
        $crawler = $client->request('GET', '/tasks/'.$id.'/delete');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');

    }

}
