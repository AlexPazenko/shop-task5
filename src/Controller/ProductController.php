<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CreateProductFormType;
use App\Form\ProductSearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/products/{page}", defaults={"page": "1"}, name="products")
     */
    public function index($page, Request $request): Response
    {
      $sortByName = $request->get('sortbyname');
      $sortByPrice = $request->get('sortbyprice');
      $sortByManufacturer = $request->get('sortbymanufacturer');
      if (!empty($sortByName)) {
      $products = $this->getDoctrine()
        ->getRepository(Product::class)
        ->sortByName($page, $sortByName);
      } elseif (!empty($sortByPrice)) {
        $products = $this->getDoctrine()
          ->getRepository(Product::class)
          ->sortByPrice($page, $sortByPrice);
      } elseif (!empty($sortByManufacturer)) {
        $products = $this->getDoctrine()
          ->getRepository(Product::class)
          ->sortByManufacturer($page, $sortByManufacturer);
      } else {
        $products = $this->getDoctrine()
          ->getRepository(Product::class)
          ->sortByName($page, $sortByName);
      }

          return $this->render('product/products.html.twig', [
              'controller_name' => 'ProductController',
              'products' => $products,
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
          return $this->redirectToRoute('products');
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/product/{id}", name="update_product")
     */
    public function updateProduct(Request $request, Product $product): Response
    {

        $form = $this->createForm(CreateProductFormType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $product = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/product-search-results/{page}", defaults={"page": "1"}, methods={"GET"},  name="product_search_results")
     */
    public function productSearchResults($page, Request $request): Response
    {
        $products = null;
        $query = null;
        if($query = $request->get('query'))
        {
            $products = $this->getDoctrine()
              ->getRepository(Product::class)
              ->findByProductName($query, $page, $request->get('sortbyname'));

          if(!$products->getItems()) $products = null;
        }

        return $this->render('product/product_search_results.html.twig',[
            'products' => $products,
            'query' => $query
        ]);
    }
}
