<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\ServiceGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray'
                ],
                'choices' => [
                    'Websites' => 0,
                    'Servers' => 1
                ]
            ])
            ->add('address', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('public', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'text-blue-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray'
                ]
            ])
            ->add('url', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
                ]
            ])
            ->add('serviceGroup', EntityType::class, [
                'class' => ServiceGroup::class,
                'attr' => [
                    'class' => 'block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
