<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    /**
     * @Route("/users", name="user_list")
     */
    public function listAction(UserService $userService)
    {
        return $this->render('user/list.html.twig', ['users' => $userService->getList()]);
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request, UserService $userService)
    {
        // MAKE FORM
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // HANDLE REQUEST
        $form->handleRequest($request);

        // IF FORM IS VALID
        if ($form->isSubmitted() && $form->isValid()) {
            // CREATE USER & ADD FLASH MESSAGE
            $userService->create($user);
            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request, UserService $userService)
    {
        // CREATE FORM
        $form = $this->createForm(UserType::class, $user);

        // HANDLE REQUEST
        $form->handleRequest($request);

        // IF FORM IS VALID
        if ($form->isSubmitted() && $form->isValid()) {
            //EDIT USER & ADD FLASH MESSAGE
            $userService->edit($user);
            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
