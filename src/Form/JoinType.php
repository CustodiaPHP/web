<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class JoinType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		return $builder
			->add('password', PasswordType::class, [
				'label' => false,
				'required' => true,
				'attr' => [
					'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
				],
			])
			->add('repeatPassword', PasswordType::class, [
				'label' => false,
				'required' => true,
				'attr' => [
					'class' => 'block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input'
				]
			]);
	}

}
