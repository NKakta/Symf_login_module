<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
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
                        'message' => 'Username can not be empty',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your username should be at least {{ limit }} characters',
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
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 255
                    ]),
                ],
            ])
            ->add('credits', IntegerType::class, [
                //'label' => 'Telefono numeris',
                'attr' => array(
                    'placeholder' => 'Kreditai',
                ),
                'label' => false,
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
