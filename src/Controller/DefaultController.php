<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
  private $em;
  public function __construct(EntityManagerInterface $em) {
    $this->em = $em;
  }

  /**
   * @Route("/", name="front")
   */
  public function frontPage(): Response
  {
    $products = $this->em->getRepository(Product::class)->findAll();
    return $this->render('front/front.html.twig', [
      'products' => $products,
    ]);
  }


  /**
   * @Route("/login", name="login")
   */
  public function login(AuthenticationUtils $helper): Response
  {
    return $this->render('front/login.html.twig', [
      'error' => $helper->getLastAuthenticationError()
    ]);
  }

  /**
   * @Route("/logout", name="logout")
   */
  public function logout(): void
  {
    throw new \LogicException('This should never be reached!');
  }
}
