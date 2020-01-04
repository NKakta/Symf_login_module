<?php
declare(strict_types=1);

namespace App\Form;

use App\Enum\Regions;
use App\Model\AccountImportFormModel;
use App\Repository\ProductRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportFormType extends AbstractType
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'White Checker' => 'white_checker',
                    'Green Checker' => 'green_checker',

                ],
                'required' => true
            ])
            ->add('region', ChoiceType::class, [
                'choices' => [
                    Regions::getChoices()
                ],
                'required' => true
            ])
            ->add('file', FileType::class, [
                'label' => 'Accounts list file (JSON)',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccountImportFormModel::class,
            'csrf_protection' => false,
        ]);
    }
}
