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
   * @Route("/users/{page}", defaults={"page": "1"}, name="users")
   */
  public function index($page, Request $request): Response
  {
    $users = $this->getDoctrine()
      ->getRepository(User::class)
      ->filterByRole($page, $request->get('filterby'), $request->get('sortuserby'));

      return $this->render('user/users.html.twig', [
        'controller_name' => 'UserController',
        'users' => $users,
      ]);
  }
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
            $user->setRoles($form->get('roles')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
          return $this->redirectToRoute('users');
        }
        return $this->render('user/user_create.html.twig', [
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
            return $this->redirectToRoute('users');
        }
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user-search-results/{page}", defaults={"page": "1"}, methods={"GET"},  name="user_search_results")
     */
    public function userSearchResults($page, Request $request): Response
    {
      $users = null;
      $query = null;
      if($query = $request->get('query'))
      {
        $users = $this->getDoctrine()
          ->getRepository(User::class)
          ->findByUsersEmail($query, $page, $request->get('filterby'), $request->get('sortuserby'));

        if(!$users->getItems()) $users = null;
      }

      return $this->render('user/user_search_results.html.twig',[
        'users' => $users,
        'query' => $query
      ]);
    }
  }
