<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testList()
    {
	    $client = static::createClient(array(), array(
		    'PHP_AUTH_USER' => 'qleilde',
			'PHP_AUTH_PW' => 'qleilde',
		));

		$client->request('GET', '/tasks');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
