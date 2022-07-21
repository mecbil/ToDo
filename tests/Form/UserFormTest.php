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

    public function testAddEditDeleteUser(): void
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

        //Recuperer le LastId
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
           
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'testUser'])
        ;
        $id = $user->getId();
        
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

        // // Supprimer le User
        $crawler = $client->request('GET', '/users/'.$id.'/delete');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs');

    }
}
