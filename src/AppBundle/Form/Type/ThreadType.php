<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreadType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text');
		$builder->add('post', 'textarea', array('attr' => array('rows' => 28)));
		$builder->add('save', 'submit', array('label' => 'Create Thread'));
		$builder->getForm();
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'AppBundle\Entity\Blog'
			));
	}

	public function getName()
	{
		return 'forum';
	}
}

?>