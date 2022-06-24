<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserFixture extends Fixture implements FixtureGroupInterface
{
  public const ADMIN_USER_REFERENCE = 'user';
  public function __construct(UserPasswordEncoderInterface $password_encoder)
  {
    $this->password_encoder = $password_encoder;
  }
  public function load(ObjectManager $manager): void
  {
    foreach ($this->getUserData() as [$email, $firstName, $lastName, $password, $roles])
    {
      $user = new User();
      $user->setEmail($email);
      $user->setFirstName($firstName);
      $user->setLastName($lastName);
      $user->setPassword($this->password_encoder->encodePassword($user, $password));
      $user->setRoles($roles);
      $manager->persist($user);
      $manager->flush();
      /*$this->addReference(self::ADMIN_USER_REFERENCE, $user);*/
    }

    $manager->flush();
  }

  private function getUserData(): array
  {
    return [
      ['john.wayne@symf4.loc', 'John', 'Wayne', 'password', ['ROLE_ADMIN']],
      ['peater.parker@symf4.loc', 'Peter', 'Parker', 'password', ['ROLE_ADMIN']],
      ['john.doe@symf4.loc', 'John', 'Doe', 'password', ['ROLE_MANAGER']],
      ['bob.marley@symf4.loc', 'Bob', 'Marley', 'password', ['ROLE_MANAGER']],
      ['steve.jobs@symf4.loc', 'Steve', 'Jobs', 'password', ['ROLE_SALESMAN']],
      ['willem.dafoe@symf4.loc', 'Willem', 'Dafoe', 'password', ['ROLE_SALESMAN']],
      ['joaquin.phoenix@symf4.loc', 'Joaquin', 'Phoenix', 'password', ['ROLE_CUSTOMER']],
      ['jared.leto@symf4.loc', 'Jared', 'Leto', 'password', ['ROLE_CUSTOMER']],
    ];
  }

  public static function getGroups(): array
  {
  return ['user'];
  }
}
