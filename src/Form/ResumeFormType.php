<?php

namespace App\Form;

use App\Entity\Choises;
use App\Entity\Resume;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ResumeFormType extends AbstractType
{
    private $userRepo;

    public function __construct(UserRepository $repository)
    {
        $this->userRepo = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /*$qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_User, 'u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.$role.'"%');

        $users = $qb->getQuery()->getResult();*/
        $builder
            ->add('area', ChoiceType::class, [
                'label' => 'Ieškomo darbo sritis: ',
                'choices' => [
                    'Programuotojas' => 'Programuotojas',
                    'Apsauginis' => 'Apsauginis',
                    'Analitikas' => 'Analitikas',
                    'Vairuotojas' => 'Vairuotojas',
                    'Virėjas' => 'Virejas',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('education', ChoiceType::class, [
                'label' => 'Įgytas išsilavinimas: ',
                'choices' => [
                    'Pagrindinis' => 'Pagrindinis',
                    'Vidurinis' => 'Vidurinis',
                    'Profesinis' => 'Profesinis',
                    'Aukštasis' => 'Aukstasis',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('languages', ChoiceType::class, [
                'label' => 'Kalbos kuriomis šnekate, rašote: ',
                'choices' => [
                    'Lietuvių k.' => 'Lietuviskai',
                    'Anglų k.' => 'Angliskai',
                    'Rusų k.' => 'Rusiskai',
                    'Vokiečių k.' => 'Vokiskai',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            /*->add('sritis', EntityType::class, [
                'label' => 'Ieškomo darbo sritis: ',
                'class' => Choises::class,

                'choice_label' => 'sritis',

                'multiple' => false,
                'expanded' => true,
            ])
            ->add('issilavinimas', EntityType::class, [
                'label' => 'Įgytas išsilavinimas: ',
                'class' => Choises::class,

                'choice_label' => 'issilavinimas',

                'multiple' => false,
                'expanded' => true,
            ])
            ->add('kalbos', EntityType::class, [
                'label' => 'Kalbos kuriomis šnekate, rašote: ',
                'class' => Choises::class,


                'choice_label' => 'issilavinimas',

                'multiple' => false,
                'expanded' => true,
            ])*/
            ->add('salary', TextType::class, [
                'label' => 'Pageidaujamas atlyginimas: ',
                /*'choices' => [
                    '500e' => 500,
                    '1000e' => 1000,
                    '2000e' => 2000,
                ],
                'multiple' => false,
                'expanded' => true,*/
            ])
            ->add('experience', ChoiceType::class, [
                'label' => 'Patirtis',
                'choices' => [
                    '1m' => 1,
                    '2m' => 2,
                    '3m' => 3,
                    '5m' => 5,
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('aboutYou', TextareaType::class, [
                'label' => 'Trumpai papasakokite apie save',
            ])
            ->add('hideFrom', EntityType::class, [
                'label' => 'Pasirinkite kam nerodyti jūsų CV, jeigu tokių žmonių yra',
                'choice_label' => 'lastName',
                'class' => User::class,
                'choices' => [
                    $this->userRepo->findByRoleEmployer()
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('isMain', ChoiceType::class, [
                'label' => 'Ar norite, jog šis CV būtų isMain? ',
                'choices' => [
                    'Taip' => true,
                    'Ne' => false,
                ],
                'multiple' => false,
                'expanded' => true,
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
        ]);
    }
}
