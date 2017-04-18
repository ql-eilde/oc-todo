<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;

class TaskControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $client;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'qleilde',
            'PHP_AUTH_PW' => 'qleilde',
        ));
    }

    public function testList()
    {
		$this->client->request('GET', '/tasks');

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', '/tasks/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $faker = Factory::create();

        $form['task[title]'] = $faker->sentence();
        $form['task[content]'] = $faker->text();

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testEdit()
    {
        $tasks = $this->em->getRepository('AppBundle:Task')->findAll();
        $task = end($tasks);

        $crawler = $this->client->request('GET', '/tasks/'.$task->getId().'/edit');

        $form = $crawler->selectButton('Modifier')->form();

        $faker = Factory::create();

        $form['task[title]'] = $faker->sentence();
        $form['task[content]'] = $faker->text();

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testTaskToggle()
    {
        $tasks = $this->em->getRepository('AppBundle:Task')->findAll();
        $task = end($tasks);

        $this->client->request('GET', '/tasks/'.$task->getId().'/toggle');

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testTaskDelete()
    {
        $tasks = $this->em->getRepository('AppBundle:Task')->findAll();
        $task = end($tasks);

        $this->client->request('GET', '/tasks/'.$task->getId().'/delete');

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }
}
