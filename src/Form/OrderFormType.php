<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\Order;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OrderFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', EntityType::class, [
                'choice_label' => 'email',
                'class' => User::class,
                'choices' => [
                    $this->userRepo->findAll()
                ],
            ])
            ->add('craftsman', EntityType::class, [
                'choice_label' => 'email',
                'class' => User::class,
                'choices' => [
                    $this->userRepo->findAll()
                ],
            ])
            ->add('content', TextType::class)
            ->add('dateFrom', DateType::class)
            ->add('dateTo', DateType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
