<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $doctrine)
    {
        $this->em = $entityManager;
        $repoTask = $doctrine->getRepository(Task::class);
        $this->repo = $repoTask;
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        $user = $this->getUser();
        $userRole = $user->getRoles();

        $repoTask = $this->repo;
        // return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll()]);

        // Role = Admin;
        if ($userRole == ['ROLE_ADMIN']) {
            return $this->render('task/list.html.twig', ['tasks' => $repoTask->findAll()]);
        }
        // Role = User;
        return $this->render('task/list.html.twig', ['tasks' => $repoTask->findBy(['author' => $user])]);
    }   

    /**
     * @Route("/tasks/done", name="task_list_done")
     */
    public function listActionDone()
    {
        $user = $this->getUser();
        $userRole = $user->getRoles();
        $repoTask = $this->repo;
        // return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll()]);
        
        
        // Role = Admin;
        if ($userRole == ['ROLE_ADMIN']) {
            return $this->render('task/list.html.twig', ['tasks' => $repoTask->findBy(['isDone' => true])]);
        }
        // Role = User;
        return $this->render('task/list.html.twig', ['tasks' => $repoTask->findBy(['author' => $user, 'isDone' => true])]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $user = $this->getUser();

        $task = new Task();
        
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager();

            // Add an author automatically
            $task->setAuthor($user);

            $this->em->persist($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction($id, Request $request)
    {
        // Trouver l'enregistrement avec l'ID $id
        $repoTask = $this->repo;
        $task = $repoTask->find($id);

        // Editer l'enregistrement
        $this->denyAccessUnlessGranted("edit", $task);
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $this->getDoctrine()->getManager()->flush();
            $this->em->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction($id)
    {
        // Trouver l'enregistrement avec l'ID $id
        $repoTask = $this->repo;
        $task = $repoTask->find($id);

        // Modifer l'enregistrement
        $this->denyAccessUnlessGranted("edit", $task);
        $task->toggle(!$task->isDone());
        // $this->getDoctrine()->getManager()->flush();
        $this->em->flush();

        if ($task->isisDone() === false) {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme non termitée.', $task->getTitle()));
        } else {
            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        }
        
        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction($id)
    {
        // Trouver l'enregistrement avec l'ID $id
        $repoTask = $this->repo;
        $task = $repoTask->find($id);
        
        // Supprimer l'enregistrement
        $this->denyAccessUnlessGranted("delete", $task);
        // $em = $this->getDoctrine()->getManager();
        $this->em->remove($task);
        $this->em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
