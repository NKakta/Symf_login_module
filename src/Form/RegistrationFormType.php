<?php

namespace App\Form;


namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                //'label' => 'Vardas',
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Jūsų vardas',
                ),
            ])
            ->add('lastName', TextType::class, [
                //'label' => 'Pavardė',
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Jūsų pavardė',
                ),
            ])
            ->add('username', TextType::class, [
                //'label' => 'Prisijungimo vardas',
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Slapyvardis',
                ),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Slapyvardis negali būti tuščias',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Slapyvardis turi būti, bent 3 simbolių',
                        'max' => 255
                    ]),
                ],
            ])
            ->add('phoneNum', TextType::class, [
                //'label' => 'Telefono numeris',
                'attr' => array(
                    'placeholder' => 'Telefono numeris',
                ),
                'label' => false,
            ])
            ->add('email', EmailType::class, [
                //'label' => 'Elektroninis adresas',
                'attr' => array(
                    'placeholder' => 'Elektroninis adresas',
                ),
                'label' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Slaptažodis',
                    ),
                    //'label' => 'Slaptažodis',
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Pakartoti slaptažodį',
                    ),
                    //'label' => 'Pakartoti slaptažodį'
                ],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Prašome įvesti slaptažodį',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Slaptažodis turi būti bent {{ limit }} simbolių',
                        'max' => 255
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                //'label' => 'Kas jūs esate?',
                'label' => false,
                'choices' => [
                    'Ieškau darbuotojų' => 'ROLE_EMPLOYER',
                    'Ieškau darbo' => 'ROLE_EMPLOYEE',
                ],
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}