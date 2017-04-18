<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;

class UserControllerTest extends WebTestCase
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
        $this->client->request('GET', '/users');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', '/users/create');

        $form = $crawler->selectButton('Ajouter')->form();

        $faker = Factory::create();
        $pass = $faker->password;

        $form['user[username]'] = $faker->userName;
        $form['user[password][first]'] = $pass;
        $form['user[password][second]'] = $pass;
        $form['user[email]'] = $faker->email;

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }

    public function testEdit()
    {
        $users = $this->em->getRepository('AppBundle:User')->findAll();
        $user = end($users);

        $crawler = $this->client->request('GET', '/users/'.$user->getId().'/edit');

        $form = $crawler->selectButton('Modifier')->form();

        $faker = Factory::create();
        $pass = $faker->password;

        $form['user[username]'] = $faker->userName;
        $form['user[password][first]'] = $pass;
        $form['user[password][second]'] = $pass;
        $form['user[email]'] = $faker->email;

        $this->client->submit($form);

        $this->assertTrue($this->client->getResponse()->isRedirect());
    }
}
