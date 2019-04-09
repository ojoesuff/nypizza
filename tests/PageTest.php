<?php

 namespace App\Tests;

 use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

 class PageTest extends WebTestCase {

    public function testShowIndex() {
        $client = static::createClient();
        $client->request('GET', '/index');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
 }

