<?php

namespace App\Form;

use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ItemFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manufacturer', ChoiceType::class, [
                'label' => 'Manufacturer',
                'choices' => [
                    'Boeing' => 'boeing',
                    'Lockheed Martin' => 'lockheed_martin',
                    'Kanye West' => 'kanye_west',
                ],
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('price', NumberType::class)
            ->add('model', TextType::class)
            ->add('name', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
