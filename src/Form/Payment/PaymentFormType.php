<?php
declare(strict_types=1);

namespace App\Form\Payment;

use App\Entity\Order;
use App\Entity\Product;
use App\Model\PaymentModel;
use App\Repository\AccountRepository;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PaymentFormType extends AbstractType
{
    /**
     * @var ProductRepository
     */
    private $productRepo;
    /**
     * @var AccountRepository
     */
    private $accountRepo;

    public function __construct(ProductRepository $productRepo, AccountRepository $accountRepo)
    {
        $this->productRepo = $productRepo;
        $this->accountRepo = $accountRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('payment_method',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Assert\Choice([
                            'choices' => [
                                Order::TYPE_PAYMENT_CRYPTO,
                                Order::TYPE_PAYMENT_PAYPAL,
                            ]
                        ]),
                    ]
                ]
            )
            ->add('quantity',
                NumberType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new Assert\GreaterThan(0),
                    ]
                ]
            )
            ->add('in_stock', NumberType::class)
            ->add('total_price', MoneyType::class)
            ->add('original_price', MoneyType::class)
            ->add(
                'product_id',
                EntityType::class,
                [
                    'class' => Product::class,
                    'choices' => $this->productRepo->findAll(),
                    'required' => true
                ]
            )
            ->add('email', EmailType::class)

        ;
    }

    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaymentModel::class,
            'csrf_protection' => false,
        ]);
    }
}
