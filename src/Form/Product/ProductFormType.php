<?php
declare(strict_types=1);

namespace App\Form\Product;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', MoneyType::class)
            ->add('category', EntityType::class, [
                'choice_label' => 'name',
                'class' => Category::class,
                'choices' => [
                    $this->categoryRepository->findAll()
                ],
            ])
            ->add('photoFilename', ChoiceType::class, [
                'choices' => [
                    '1.png' => '1.png',
                    '2.png' => '2.png',
                    '3.png' => '3.png',
                    '4.png' => '4.png',
                    '5.png' => '5.png',
                    '6.png' => '6.png',
                    '7.png' => '7.png',
                    '8.png' => '8.png',
                    '9.png' => '9.png',
                ],
            ])
            ->add(
                'daysNotPlayed',
                IntegerType::class,
                [
                    'required' => false
                ]
            )
            ->add(
                'minLevel',
                IntegerType::class,
                [
                    'required' => false
                ]
            )
            ->add(
                'minChampCount',
                IntegerType::class,
                [
                    'required' => false
                ]
            )
            ->add(
                'minSkinCount',
                IntegerType::class,
                [
                    'required' => false
                ]
            )
            ->add(
                'minRpCount',
                IntegerType::class,
                [
                    'required' => false
                ]
            )
            ->add('ranks', ChoiceType::class, [
                'required' => false,
                'multiple' => true,
                'choices' => [
                    'Unranked' => 'Unranked',
                    'Bronze 1' => 'Bronze 1',
                    'Bronze 2' => 'Bronze 2',
                    'Bronze 3' => 'Bronze 3',
                    'Bronze 4' => 'Bronze 4',
                    'Bronze 5' => 'Bronze 5',
                    'Silver 1' => 'Silver 1',
                    'Silver 2' => 'Silver 2',
                    'Silver 3' => 'Silver 3',
                    'Silver 4' => 'Silver 4',
                    'Silver 5' => 'Silver 5',
                    'Gold 1' => 'Gold 1',
                    'Gold 2' => 'Gold 2',
                    'Gold 3' => 'Gold 3',
                    'Gold 4' => 'Gold 4',
                    'Gold 5' => 'Gold 5',
                    'Platinum 1' => 'Platinum 1',
                    'Platinum 2' => 'Platinum 2',
                    'Platinum 3' => 'Platinum 3',
                    'Platinum 4' => 'Platinum 4',
                    'Platinum 5' => 'Platinum 5',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' => false,
        ]);
    }
}
