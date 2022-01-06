<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
         * @Route("/create/product", name="create_product")
     */
    public function createProduct(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(CreateProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $product->setName($form->get('name')->getData());
            $product->setManufacturer($form->get('manufacturer')->getData());
            $product->setPrice($form->get('price')->getData());
            $product->setDescription($form->get('description')->getData());
            $product->setCreation(new \DateTime());
            $product->setModified(new \DateTime());
            $product->setQuantity($form->get('quantity')->getData());
            $product->setUserCreated($form->get('user_created')->getData());


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();
            /*return $this->redirectToRoute('products');*/
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
        ]);
    }
}
