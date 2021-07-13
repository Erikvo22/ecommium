<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProcessesControllerTest extends WebTestCase
{
    public function ntestGetAllProcesses()
    {
        $client = static::createClient();
        $client->request('GET', '/data');
        $this->assertResponseIsSuccessful();
    }

    public function ntestCreateProcess()
    {
        $client = static::createClient();
        $parameters = array('type' => 1, 'input' => 'hola que tal');
        $client->request('POST', '/create', $parameters);
        $this->assertResponseIsSuccessful();
    }

    public function testRunProccess()
    {
        $client = static::createClient();
        $parameters = array('id' => '60edf357cf12000057002432');
        $client->request('PUT', '/run', $parameters);
        $this->assertResponseIsSuccessful();
    }
}