<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Services\CreateOrderPDF;
use App\Controller\OrderController;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OrderFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
  private $orderController;
  private $createOrderPDF;
  public function __construct(OrderController $orderController, CreateOrderPDF $createOrderPDF)
  {
    $this->orderController = $orderController;
    $this->createOrderPDF = $createOrderPDF;
  }

  public function load(ObjectManager $manager): void
  {
    foreach ($this->getOrderData() as [$salesmanId, $customerId, $paid, $description, $pdfUrl])
    {
      $order = new Order();
      $salesman = $manager->getRepository(User::class)->find($salesmanId);
      $order->setSalesman($salesman);
      $customer = $manager->getRepository(User::class)->find($customerId);
      $order->setCustomer($customer);
      $order->setPaid($paid);
      $order->setDescription($description);
      $order->setPdf($pdfUrl);
      $manager->persist($order);
      $manager->flush();
      $this->createOrderPDF->createPDF($order);
    }

  }

  private function getOrderData(): array
  {
    return [
      [5,7, 0, 'He promised to pay', '/'.$this->orderController::Assets.'/'.$this->orderController::PDF.'/order-1.pdf'],
      [5,7, 1, 'He promised to pay', '/'.$this->orderController::Assets.'/'.$this->orderController::PDF.'/order-2.pdf'],
      [6,8, 0, null , '/'.$this->orderController::Assets.'/'.$this->orderController::PDF.'/order-3.pdf'],
      [6,8, 1, 'He promised to pay', '/'.$this->orderController::Assets.'/'.$this->orderController::PDF.'/order-4.pdf'],
      [5,8, 1, null , '/'.$this->orderController::Assets.'/'.$this->orderController::PDF.'/order-5.pdf'],
      [6,7, 0, null , '/'.$this->orderController::Assets.'/'.$this->orderController::PDF.'/order-6.pdf'],
    ];
  }

  public static function getGroups(): array
  {
    return ['order'];
  }

  public function getDependencies()
  {
    return [
      UserFixture::class,
    ];
  }

}
