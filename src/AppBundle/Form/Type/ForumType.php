<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text');
		$builder->add('category', 'entity', array('label' => 'Select a Category', 'class' => 'AppBundle:Category', 'property' => 'title'));
		$builder->add('slug', 'text');
		$builder->add('img', 'url', array('label' => 'Image'));
		$builder->add('info', 'textarea', array('attr' => array('rows' => 6)));
		$builder->add('weight', 'integer', array("attr" => array('style' => 'width: 80px;')));
		$builder->add('save', 'submit', array('label' => 'Create Forum'));
		$builder->getForm();
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'AppBundle\Entity\Forum'
			));
	}

	public function getName()
	{
		return 'forum';
	}
}

?>