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
     * @Route("/products", name="products")
     */
    public function showProducts(Request $request): Response
    {

        $form = $this->createForm(ProductSearchFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

        }


        return $this->render('product/products.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
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

            /*return $this->redirectToRoute('task_success');*/
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

            /*return $this->redirectToRoute('task_success');*/
        }

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/products", name="product_search")
     */
    public function searchBar(Request $request): Response
    {
        $form = $this->createFormBuilder(null)
            ->add('squery', TextType::class)
            ->add('search', SubmitType::class,[
                'label' => 'Search product',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
            ->setAction($this->generateUrl('target_route'))
            ->setMethod('POST')

            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            $searchData = $form->get('squery')->getData();
            return $this->redirectToRoute('products', array('s' => $searchData));
        }

        return $this->render('product/products.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
