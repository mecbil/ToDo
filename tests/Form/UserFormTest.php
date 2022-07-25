<?php

namespace App\Tests\Form;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

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

    public function lastId($name)
    {
        //Recuperer le LastId
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
            
        $usertest = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => $name])
        ;
        $id = $usertest->getId();

        return $id;
    }

    // Tester la page Users sans etre connecter
    public function testDisplayTaskPageUsersNotConnected(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testDisplayUserPageAdminConnected(): void
    {
        $client = $this->connect();
               
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List,");
        
        $client->request('GET', '/users');

        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }

    public function testDisplayUserPageUserConnected(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Delaunay';// Nom USER à changer en fonction de votre fixture
        $form['_password'] = 'Azerty1+';

        $client->submit($form);
               
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List,");
        
        $client->request('GET', '/users');

        $this->assertResponseRedirects();
        $client->followRedirect();
      
        $this->assertSelectorTextContains('h1', "Bienvenue sur Todo List,");

    }


    public function testAddUser(): void
    {
        $client = $this->connect();
        //Add user
        $this->assertResponseRedirects();
        $client->followRedirect();

        $crawler = $client->request('GET', '/users/create');
        $this->assertSelectorTextContains('h1', 'Créer un utilisateur');

        $form = $crawler->selectButton('Ajouter')->form();
        $form["user[username]"] = 'testUser';
        $form["user[password][first]"] = 'Azerty1+';
        $form["user[password][second]"] = 'Azerty1+';
        $form["user[roles]"] = 'ROLE_USER';
        $form["user[email]"] = 'testUser@test.fr';

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testEditUser(): void
    {
        $client = $this->connect();
        $this->assertResponseRedirects();
        $client->followRedirect();

        $id = $this->lastId('testUser');
        
        // Editer le User
        $crawler = $client->request('GET', '/users/'.$id.'/edit');
        // $this->assertSelectorTextContains('h1', 'Modifier');
        $form = $crawler->selectButton('Modifier')->form();
        $form["user[username]"] = 'testUser2';
        $form["user[password][first]"] = 'Azerty1+';
        $form["user[password][second]"] = 'Azerty1+';
        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteUser(): void
    {       
        $client = $this->connect();
        $this->assertResponseRedirects();
        $client->followRedirect();

        $id = $this->lastId('testUser2');

        // Supprimer le User
        $crawler = $client->request('GET', '/users/'.$id.'/delete');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');

    }

    public function testDeleteUserNotFound(): void
    {
        $client = $this->connect();
        
        $id = '81';
        
        // Supprimer le User
        $crawler = $client->request('GET', '/users/'.$id.'/delete');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        
    }

    public function testUserEditNotFound()
    {
        $client = $this->connect();

        $id = '81';
        
        // Editer le User
        $crawler = $client->request('GET', '/users/'.$id.'/edit');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

    }

}
