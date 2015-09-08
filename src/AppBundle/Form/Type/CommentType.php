<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text');
		$builder->add('text', 'textarea', array('attr' => array('rows' => 28)));
		$builder->add('save', 'submit', array('label' => 'Post Response'));
		$builder->getForm();
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'AppBundle\Entity\Comment'
			));
	}

	public function getName()
	{
		return 'comment';
	}
}

?>