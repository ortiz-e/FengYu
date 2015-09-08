<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class PollType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text');
		$builder->add('choices', 'collection', 
			array(
				'label_attr' => array(
					'style' => 'vertical-align: top;'
				), 
				'type' => 'text', 
				'required' => false, 
				'allow_add' => true, 
				'allow_delete' => true, 
				'delete_empty' => true, 
				'attr' => array(
					'style' => 'display: inline-block; margin: 0 0 20px 20px; width: 200px;'
				)));
		$builder->get('choices')
				->addModelTransformer(new CallbackTransformer(
					//organize output to form
					function ($originalChoices){
						if(!$originalChoices) return;
						$output = array();
						foreach($originalChoices as $choice){
							$output[] = $choice->getTitle();
						}
						return $output;
					},
					function ($submittedChoices){
						return $submittedChoices;
					}
					));
		$builder->add('description', 'textarea', array('attr' => array('rows' => 6)));
		$builder->add('save', 'submit', array('label' => 'Create Poll'));
		$builder->getForm();
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'AppBundle\Entity\Poll'
			));
	}

	public function getName()
	{
		return 'poll';
	}
}

?>