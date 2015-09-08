<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Model;
use AppBundle\Model\Core;
use AppBundle\Entity\User;

class UserController extends Controller
{
	/**
	 * @Route("/user/{id}", name="profileView", defaults={"id": 0}, requirements={"id" : "\d+"})
	 */
	public function profileView($id)
	{
		if($id == 0){
			$user = $this->getUser();
		}
		else {
			$user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
		}
		if(empty($user)) {
			return Core::redirect($this, 'homepage');
		}
		$user->addView();
		$em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
		$user->link = Core::userLink($this, $user);
		$user->days = floor((time() - $user->getJoinDate()->getTimeStamp()) / 86400);
		return $this->render(Core::themePath($this) . '/default/profile.html.twig',
            array('user' => $user, 'globals' => Core::getGlobals($this))
            );
	}

	/**
	 * @Route("/user/edit/{id}", name="editProfileForm", defaults={"id": 0}, requirements={"id" : "\d+"})
	 */
	public function editProfileForm(Request $request, $id)
	{
		
		if(!Core::loggedIn($this)) return Core::redirect($this, 'referer');

		if($id == 0)
		{
			$user = $this->getUser();
			$id = $user->getId();
		}
		else 
		{
			$user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
		}
		if(empty($user)) 
		{
			return Core::redirect($this, 'homepage');
		}
		if(!Core::isAdmin($this) && $id != $this->getUser()->getId()) return Core::redirect($this, 'referer', null, 'Error', 'Cannot edit other people\'s profiles!', 5, 'panel-danger');

		$form = $this->createFormBuilder($user);
		if(Core::isAdmin($this))
		{
			$form = $form
				->add('username', 'text', array('required' => false,'attr' => array('style' => 'max-width: 200px;')))
				->add('roles', 'choice', array(
					'multiple' => true, 
					'expanded' => true, 
					'choices' => array(
						'ROLE_ROOT' => 'Root',
						'ROLE_ADMIN' => 'Administrator',
						'ROLE_MODERATOR' => 'Moderator', 
						'ROLE_USER' => 'Regular User', 
						'ROLE_BANNED' => 'Banned')));
			if(!$user->getModForums()){
				$user->setModForums(array());
				$cats = $this->getDoctrine()
	            ->getRepository('AppBundle:Category')
	            ->findBy(array(), array('weight' => 'ASC'));
	            foreach($cats as $cat){
	            	foreach($cat->getForums() as $forum)
	            		$form = $form->add($forum->getSlug(), 'choice', array(
	            			'multiple' => true,
	            			'expanded' => true,
	            			'choices' => array('0' => 'Read Only', '1' => 'Close/Sticky', '2' => 'Edit Posts/Threads', '3' => 'Add/Edit Announcements')));
	            }
			}
		}

		$form = $form
			->add('realName', 'text', array('required' => false,'attr' => array('style' => 'max-width: 200px;')))
			->add('gender', 'choice', array('multiple' => false, 'expanded' => true, 'choices' => array('0' => 'Male', '1' => 'Female', '2' => 'Other', '3' => 'Unknown')))
			->add('birthDate', 'birthday')
			->add('status', 'text', array('attr' => array('style' => 'max-width: 400px;')))
			->add('userpic', 'url', array('attr' => array('style' => 'max-width: 400px;')))
			->add('location', 'text', array('attr' => array('style' => 'max-width: 300px;')))
			->add('theme', 'entity', array('attr' => array('style' => 'max-width: 200px;'), 'label' => 'Select a Website Theme', 'class' => 'AppBundle:Theme', 'property' => 'name'))
			->add('favoriteGames', 'textarea', array('required' => false,'attr' => array('rows' => 5)))
			->add('nowPlaying', 'textarea', array('required' => false,'attr' => array('rows' => 5)))
			->add('aboutMe', 'textarea', array('attr' => array('rows' => 25)))
			->add('save', 'submit', array('label' => 'Save'))
			->getForm();
		if(!$request->isMethod('POST'))
        {
        	if(Core::isAdmin($this))
        		return $this->render(Core::themePath($this) . '/default/userForm.html.twig',
            array('form' => $form->createView(), 'globals' => Core::getGlobals($this))
            );
            return Core::doForm($this, $form, 'Edit your Profile');
        }
        else {
            $form->bind($request);
            $data = $form->getData();
            if(in_array('ROLE_ROOT', $data->getRoles()) && $data->getId() !== 1) return Core::redirect($this, 'profileView', array('id' => $id), 'Error!', 'The ROOT role can only be assigned to the root user.');
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            return Core::redirect($this, 'profileView', array('id' => $id), 'Success!', 'The profile has been updated successfully. You are now being redirected to the profile.', 5, 'panel-success');
        }
	}
}

?>