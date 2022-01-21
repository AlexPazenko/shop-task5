<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CreateUserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserController extends AbstractController
{
    /**
     * @Route("/create/user", name="create_user")
     */
    public function createUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(CreateUserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setEmail($form->get('email')->getData());
            $user->setFirstName($form->get('first_name')->getData());
            $user->setLastName($form->get('last_name')->getData());
            $user->setUserRole($form->get('user_role')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            /*return $this->redirectToRoute('users');*/
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/update/user/{id}", name="update_user")
     */
    public function updateUser(Request $request, User $user): Response
    {

        $form = $this->createForm(CreateUserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            /*return $this->redirectToRoute('users');*/
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }

}
