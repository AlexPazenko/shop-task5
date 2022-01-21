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
     * @Route("/order", name="order")
     */
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
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
}
