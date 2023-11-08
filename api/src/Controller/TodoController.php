<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Factory\TodoFactory;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route(path: "/todos", name: "todos_")]
class TodoController extends AbstractController
{
    public function __construct(private TodoRepository $todos, private SerializerInterface $serializer)
    {
    }

    #[Route(path: "", name: "getTodosAction", methods: ["GET"])]
    function getTodosAction(): Response
    {
        $data = $this->todos->findAll();

        return $this->json($data);
    }

    #[Route(path: "/{id}", name: "getTodoAction", methods: ["GET"])]
    function getTodoAction(EntityManagerInterface $em, int $id): Response
    {
        $todo = $em->getRepository(Todo::class)->find($id);
        if (!$todo) {
            throw $this->createNotFoundException("Todo not found for id: " . $id);
        }

        return $this->json($todo);
    }

    #[Route(path: "/{id}", name: "editTodoAction", methods: ["PUT"])]
    function editTodoAction(EntityManagerInterface $em, int $id, Request $request): Response
    {
        $todo = $em->getRepository(Todo::class)->find($id);
        if (!$todo) {
            throw $this->createNotFoundException("Todo not found for id: " . $id);
        }

        $data = $this->serializer->deserialize($request->getContent(), Todo::class, "json");

        $todo->setTitle($data->getTitle());
        $todo->setDescription($data->getDescription());
        $todo->setCompleted($data->isCompleted());


        $em->flush();

        return $this->redirectToRoute('todos_getTodosAction');
    }

    #[Route(path: "/{id}/complete", name: "changeCompletionStatusAction", methods: ["PUT"])]
    function changeCompletionStatusAction(EntityManagerInterface $em, int $id): Response
    {
        $todo = $em->getRepository(Todo::class)->find($id);
        if (!$todo) {
            throw $this->createNotFoundException("Todo not found for id: " . $id);
        }

        $todo->setCompleted(!$todo->isCompleted());

        $em->flush();

        return $this->redirectToRoute('todos_getTodosAction');
    }

    #[Route(path: "/{id}", name: "deleteTodoAction", methods: ["DELETE"])]
    function deleteTodoAction(EntityManagerInterface $em, int $id): Response
    {
        $todo = $em->getRepository(Todo::class)->find($id);
        if (!$todo) {
            throw $this->createNotFoundException("Todo not found for id: " . $id);
        }

        $em->remove($todo);
        $em->flush();

        return $this->redirectToRoute('todos_getTodosAction');
    }

    #[Route(path: "", name: "createTodoAction", methods: ["POST"])]
    public function createTodoAction(EntityManagerInterface $em, Request $request): Response
    {
        $data = $this->serializer->deserialize($request->getContent(), Todo::class, "json");
        $todo = TodoFactory::create($data->getTitle(), $data->getDescription(), $data->isCompleted());
        $em->persist($todo);
        $em->flush();

        return $this->json([], 201, ["Location" => "/todos/" . $todo->getId()]);
    }
}
