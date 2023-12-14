<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Measurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('temperature',
                NumberType::class, [
                    'scale' => 5,
                    'html5' => true,
                ])
            ->add('windSpeed',NumberType::class, [
                'scale' => 2,
                'html5' => true,
            ])
            ->add('pressure', NumberType::class, [
                'scale' => 2,
                'html5' => true,
            ])
            ->add('humidity', NumberType::class, [
                    'scale' => 2,
                    'html5' => true,
                ])
            ->add('weatherType', ChoiceType::class, [
                'choices' => [
                    'Sunny' => 1,
                    'Cloudy' => 2,
                    'Rainy' => 3,
                    'Snowy' => 4,
                    'Stormy ' => 5,
                    'Foggy'  => 6,
                    'Windy' => 7,
                    'Hail' => 8
                ]
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
