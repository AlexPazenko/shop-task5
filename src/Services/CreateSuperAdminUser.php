<?php
namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateSuperAdminUser
{
  private $em;
  private $encoder;
  public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
    $this->em = $em;
    $this->encoder = $encoder;
  }
  public function createUser (string $firstName, string $lastName, string $email, string $password)
  {
    $user = new User();
    $user->setEmail($firstName);
    $user->setFirstName($lastName);
    $user->setLastName($email);
    $new_password = $this->encoder->encodePassword($user, $password);
    $user->setPassword($new_password);
    $user->setRoles(User::SUPER_ADMIN);
    $em = $this->em;
    $em->persist($user);
    $em->flush();
  }
}