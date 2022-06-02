<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OrderFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    foreach ($this->getOrderData() as [$salesman_id, $customer_id, $paid, $description])
    {
      $order = new Order();
      $salesman = $manager->getRepository(User::class)->find($salesman_id);
      $order->setSalesman($salesman);
      $customer = $manager->getRepository(User::class)->find($customer_id);
      $order->setCustomer($customer);
      $order->setPaid($paid);
      $order->setDescription($description);
      $manager->persist($order);
    }

    $manager->flush();
  }

  private function getOrderData(): array
  {
    return [
      [5,7, 0, 'He promised to pay'],
      [5,7, 1, 'He promised to pay'],
      [6,8, 0, null ],
      [6,8, 1, 'He promised to pay'],
      [5,8, 1, null ],
      [6,7, 0, null ],
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
