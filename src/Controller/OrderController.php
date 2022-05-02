<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CreateOrderFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
      $sortByDate = $request->get('sortbydate');

      if (!empty($sortByDate)) {
        $orders = $this->getDoctrine()
          ->getRepository(Order::class)
          ->sortByDate($sortByDate);
      } else {
        $orders = $this->getDoctrine()
          ->getRepository(Order::class)
          ->findAll();
      }

        return $this->render('order/orders.html.twig', [
          'orders' => $orders,
        ]);
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
      $fromDate = $request->get('fromDate');
      $fromDate = date("Y-m-d", strtotime($fromDate));
      $toDate = $request->get('toDate');
      $toDate = date("Y-m-d", strtotime($toDate));
      if(!empty($fromDate) && !empty($toDate) )
      {
        $orders = $this->getDoctrine()
          ->getRepository(Order::class)
          ->findByDate($fromDate, $toDate);
      }

      return $this->render('order/order_search_results.html.twig',[
        'orders' => $orders,
      ]);
    }

}
