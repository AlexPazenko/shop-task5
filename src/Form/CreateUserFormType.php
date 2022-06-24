<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CreateUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      $user = new User();
      $roles = $user->getRolesList();
      /*$roles =  array_merge(['none' => null], $roles);*/
        $builder
            ->add('email')
            ->add('first_name')
            ->add('last_name')
            ->add('password')
            /*->add('password',RepeatedType::class,[
              'type' => PasswordType::class,
            ])*/
            ->add('roles', ChoiceType::class, [
              'choices'  => $roles,
              'multiple' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Create new user'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
