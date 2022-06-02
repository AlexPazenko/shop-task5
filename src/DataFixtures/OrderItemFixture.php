<?php

namespace App\DataFixtures;

use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\Order;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderItemFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    foreach ($this->getOrderItemData() as [$product_id, $order_id, $amount])
    {
      $orderItem = new OrderItem();
      $product = $manager->getRepository(Product::class)->find($product_id);
      $orderItem->setProduct($product);
      $order = $manager->getRepository(Order::class)->find($order_id);
      $orderItem->setOrderId($order);
      $orderItem->setAmount($amount);
      $manager->persist($orderItem);
    }

    $manager->flush();
  }

  private function getOrderItemData(): array
  {
    return [
      [1,4, 2],
      [5,5, 1],
      [6,2, 3],
      [3,3, 1],
      [2,6, 3],
      [2,5, 1],
    ];
  }

  public static function getGroups(): array
  {
    return ['orderItem'];
  }

  public function getDependencies()
  {
    return [
      ProductFixture::class,
      OrderFixture::class,
    ];
  }
}
