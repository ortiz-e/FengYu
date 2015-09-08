<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model;
use AppBundle\Model\Core;
use AppBundle\Entity\User;
use AppBundle\Form\Type\RegistrationType;
use \DateTime;

class AccessController extends Controller
{
	/**
	 * @Route("/login", name="login_route")
	 */
	public function loginAction(Request $request)
	{
		$authenticationUtils = $this->get('security.authentication_utils');
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		return $this->render(
			Core::themePath($this) . '/security/login.html.twig',
			array(
				'last_username' => $lastUsername,
				'error' => $error, 'globals' => Core::getGlobals($this)
				)
			);
	}

	/**
	 * @Route("/login/verify", name="login_verify")
	 */
	public function loginVerifyAction()
	{

	}

	/**
	 * @Route("/logout", name="logout")
	 */
	public function logoutAction()
	{
		
	}

	/**
	 * @Route("/register", name="registerAction")
	 */
	public function registerAction(Request $request)
	{
		$user = new User();
		$form = $this->createForm(new RegistrationType(), $user, array(
			'action' => $this->generateUrl('registerAction')
			));

		if(!$request->isMethod('POST'))
		{
			return Core::doForm($this, $form, 'New User');
		}
		else {
			$form->bind($request);
			$data = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$encoder = $this->container->get('security.password_encoder');
			$encoded = $encoder->encodePassword($data, $data->getPassword());
			$data->setPassword($encoded);
			$data->setJoinDate(new DateTime());
			$data->setRoles(array('ROLE_USER'));
			$theme = $this->getDoctrine()
					->getRepository('AppBundle:Theme')
					->findOneById(1);
			$data->setTheme($theme);
			$em->persist($data);
			$em->flush();
			return Core::redirect($this, 'login_route', null, 'Success!', 'You have registered successfully. You are now being redirected to the login page.');
		}
	}
}