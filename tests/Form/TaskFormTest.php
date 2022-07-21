<?php

namespace App\Tests\Form;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Loader\Configurator\request;

class TaskFormTest extends WebTestCase
{
    public function testDisplayTaskPageNotConnected(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testDisplayTaskPageConnected(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $form = $crawler->selectButton('Se connecter')->form();

        $form['_username'] = 'Delaunay';
        $form['_password'] = 'Azerty1+';

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertSelectorTextContains('label', 'Title');

        $form = $crawler->selectButton('Ajouter')->form();
        $form["task[title]"] = 'title';
        $form["task[content]"] = 'content';

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');

    }
}
