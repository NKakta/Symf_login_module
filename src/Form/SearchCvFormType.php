<?php

namespace App\Form;

use App\Entity\Resume;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCvFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('area', ChoiceType::class, [
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
        ;*/
            ->add('area', ChoiceType::class, [
                'label' => 'Darbo sritis:',
                'choices' => [
                    'Programuotojas' => 'Programuotojas',
                    'Apsauginis' => 'Apsauginis',
                    'Analitikas' => 'Analitikas',
                    'Vairuotojas' => 'Vairuotojas',
                    'Virėjas' => 'Virejas',
                ],
                'multiple' => false,
                'expanded' => true,
                'required' => false,
            ])
            ->add('education', ChoiceType::class, [
                'label' => 'Reikalaujamas išsilavinimas:',
                'choices' => [
                    'Pagrindinis' => 'Pagrindinis',
                    'Vidurinis' => 'Vidurinis',
                    'Profesinis' => 'Profesinis',
                    'Aukštasis' => 'Aukstasis',
                ],
                'multiple' => false,
                'expanded' => true,
                'required' => false,
            ])
            ->add('languages', ChoiceType::class, [
                'label' => 'Kalbos:',
                'choices' => [
                    'Lietuvių k.' => 'Lietuviskai',
                    'Anglų k.' => 'Angliskai',
                    'Rusų k.' => 'Rusiskai',
                    'Vokiečių k.' => 'Vokiskai',
                ],
                'multiple' => false,
                'expanded' => true,
                'required' => false,
            ])
            ->add('salary', IntegerType::class, [
                'label' => false,
                'required' => false,
            ])
            ->add('experience', ChoiceType::class, [
                'label' => 'Reikalaujama patirtis: ',
                'choices' => [
                    '1m' => 1,
                    '2m' => 2,
                    '3m' => 3,
                    '5m' => 5,
                ],
                'multiple' => false,
                'expanded' => true,
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
