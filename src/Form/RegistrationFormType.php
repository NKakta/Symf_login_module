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
                'label' => 'Vardas',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Pavardė',
            ])
            ->add('username', TextType::class, [
                'label' => 'Prisijungimo vardas',
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
                'label' => 'Telefono numeris',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Elektroninis adresas',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Slaptažodis',
                ],
                'second_options' => [
                    'label' => 'Pakartoti slaptažodį'
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

            ->add('roles', ChoiceType::class, [
                'label' => 'Kas jūs esate?',
                'choices' => [
                    'Darbdavys' => 'ROLE_EMPLOYER',
                    'Darbuotojas' => 'ROLE_EMPLOYEE',
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