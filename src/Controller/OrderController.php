<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Services\CreateOrderPDF;
use App\Entity\User;
use App\Form\CreateOrderFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderController extends AbstractController
{
  const Assets = 'assets';
  const PDF = 'pdf';
  private $CreateOrderPDF;

  public function __construct(CreateOrderPDF $CreateOrderPDF)
  {
    $this->CreateOrderPDF = $CreateOrderPDF;

  }


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
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();
        $orderId = $order->getId();
        $order->setPdf('/'.self::Assets.'/'.self::PDF.'/order-'. $orderId .'.pdf');
        $this->CreateOrderPDF->createPDF($order);
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
            $this->CreateOrderPDF->createPDF($order);
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
