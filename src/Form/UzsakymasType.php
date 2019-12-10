<?php

namespace App\Form;

use App\Entity\Uzsakymas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UzsakymasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patvirtinimo_laiskas')
            ->add('numeris')
            ->add('bendra_suma')
            ->add('statusas')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Uzsakymas::class,
        ]);
    }
}
