<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CreateOrderFormType;
/*use Doctrine\DBAL\Types\DateTimeType;*/
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/orders/{page}", defaults={"page": "1"}, name="orders")
     */
    public function index($page, Request $request): Response
    {

      $form = $this->createFormBuilder()
        ->setAction($this->generateUrl('order_search_results'))
        ->add('fromDate', DateTimeType::class, ['label' => 'From', 'attr' => ['class' => 'js-datepicker'],])
        ->add('toDate', DateTimeType::class, ['label' => 'To'])
        ->add('search', SubmitType::class)
        ->setMethod('GET')
        ->getForm();

      $orders = $this->getDoctrine()
        ->getRepository(Order::class)
        /*->findBySort($page, $request->get('sortby'));*/
        ->findAll();

      if (!$orders) {
        return new Response('Sorry, no orders yet!!!');
      } else {
        return $this->render('order/orders.html.twig', [
          'orders' => $orders,
          'form' => $form->createView(),
        ]);
      }
    }

    /**
     * @Route("/create/order", name="create_order")
     */
    public function createOrder(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(CreateOrderFormType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $order->setSalesman($form->get('salesman')->getData());
            $order->setPaid($form->get('paid')->getData());
            $order->setDescription($form->get('description')->getData());
            $order->setCustomer($form->get('customer')->getData());
            $order->setDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
        }
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/update/order/{id}", name="update_order")
     */
    public function updateOrder(Request $request, Order $order): Response
    {
        $form = $this->createForm(CreateOrderFormType::class, $order);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $order = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
        }
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
            'form' => $form->createView(),
        ]);

    }


  /**
   * @Route("/order-search-results/{page}", defaults={"page": "1"}, methods={"GET"},  name="order_search_results")
   */
  public function orderSearchResults($page, Request $request): Response
  {
    $orders = null;
    $query = null;
    $fromDate = $request->get('form[fromDate][date][month]');
    if($query = $request->get('query'))
    {
      $orders = $this->getDoctrine()
        ->getRepository(Order::class)
        ->findByProductName($query, $page, $request->get('sortby'));

      if(!$orders->getItems()) $orders = null;
    }

    return $this->render('order/order_search_results.html.twig',[
      'orders' => $orders,
      'query' => $query
    ]);
  }

}
