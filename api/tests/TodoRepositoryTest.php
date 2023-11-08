<?php

use App\Factory\TodoFactory;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TodoRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $em;

    private TodoRepository $todos;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());
        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $container = static::getContainer();

        $this->todos = $container->get(TodoRepository::class);
    }

    protected function TearDown(): void
    {
        parent::tearDown();

        $this->em->close();
    }

    public function testCreateTodo(): void
    {
        $todo = TodoFactory::create('Test title', 'Test description');

        $this->em->persist($todo);
        $this->em->flush();
        $this->assertNotNull($todo->getId());

        $byId = $this->todos->findOneBy(["id" => $todo->getId()]);
        $this->assertEquals("Test title", $byId->getTitle());
        $this->assertEquals("Test description", $byId->getDescription());
    }
}
