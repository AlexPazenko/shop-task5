<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\DataFixtures\UserFixture;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixture extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
  public function load(ObjectManager $manager): void
  {
    foreach ($this->getProductData() as [$userCreatedId, $name, $manufacturer, $price, $description, $quantity])
    {
      $product = new Product();
      $user = $manager->getRepository(User::class)->find($userCreatedId);
      /*$product->setUserCreated($this->getReference(UserFixture::ADMIN_USER_REFERENCE));*/
      $product->setUserCreated($user);
      $product->setName($name);
      $product->setManufacturer($manufacturer);
      $product->setPrice($price);
      $product->setDescription($description);
      $product->setCreation(new \DateTime());
      $product->setModified(new \DateTime());
      $product->setQuantity($quantity);
      $manager->persist($product);
    }

    $manager->flush();
  }

  private function getProductData(): array
  {
    return [
      [5,'Дверь входная АМ-54 086П', 'Abwehr', 8699, 'Основные характеристики Дверь входная Abwehr АМ-54 086П (ТИК/Днср) Avers + Kale НЧ тик / дуб немо серебряный 2050х860 мм правая.', 100 ],
      [5,'Дверь входная Гарант 117', 'Булат', 12060, 'Основные характеристики Дверь входная Булат Гарант 117 венге горизонт темный 2050x950 мм левая.', 40 ],
      [6,'Дверь входная Стройгост 7', 'Tarimus', 4925, 'Основные характеристики Дверь входная Tarimus Стройгост 7 коричневый 2050х860мм правая.', 65 ],
      [6,'Дверь входная Гарант гладкая 156', 'Булат', 13230, 'Основные характеристики Дверь входная Булат Гарант гладкая 156 Уличная дуб темный 2050x950 мм левая.', 24 ],
      [1,'Дверь входная Мавіс дуб шале', 'Мавіс', 15560, 'Основные характеристики Дверь входная Мавіс дуб шале графит 2030x960мм правая.', 45 ],
      [6,'Двері вхідні броньовані', 'Magnoliya', 7500, 'Основные характеристики Двері вхідні броньовані 960х960х2050 мм Віп модель Магнолія', 145 ],
    ];
  }

  public static function getGroups(): array
  {
    return ['product'];
  }

  public function getDependencies()
  {
    return [
      UserFixture::class,
    ];
  }
}
