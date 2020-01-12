<?php
declare(strict_types=1);

namespace App\Form\Product;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
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
