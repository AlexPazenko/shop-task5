<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Form\CreateOrderItemFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderItemController extends AbstractController
{
    /**
     * @Route("/order/item", name="order_item")
     */
    public function index(): Response
    {
        return $this->render('order_item/index.html.twig', [
            'controller_name' => 'OrderItemController',
        ]);
    }

    /**
     * @Route("/create/order-item", name="create_order_item")
     */
    public function createOrderItem(Request $request): Response
    {
        $orderItem = new OrderItem();
        $form = $this->createForm(CreateOrderItemFormType::class, $orderItem);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $orderItem->setOrder($form->get('order')->getData());
            $orderItem->setProduct($form->get('product')->getData());
            $orderItem->setAmount($form->get('amount')->getData());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($orderItem);
            $entityManager->flush();

        }
        return $this->render('order_item/index.html.twig', [
            'controller_name' => 'OrderItemController',
            'form' => $form->createView(),
        ]);
    }
}
