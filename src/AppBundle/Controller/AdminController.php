<?php 

namespace AppBundle\Controller;

use AppBundle\Entity\Forum;
use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\ForumType;
use AppBundle\Form\Type\CategoryType;
use AppBundle\Model;
use AppBundle\Model\Core;

class AdminController extends Controller
{
	/**
	 * @Route("/admin", name="adminPanel")
	 */
	public function adminPanel(Request $request)
	{
		return Core::redirect($this, null, null, 'What to do', "Click on a link on the sidebar to begin.");
	}

	/**
     * @Route("/admin/forum/new", name="newForum")
     */
	public function newForum(Request $request)
	{
		$forum = new Forum();
		$form = $this->createForm(new ForumType(), $forum, array(
			'action' => $this->generateUrl('newForum')
			));

		if(!$request->isMethod('POST'))
		{
			return Core::doForm($this, $form, 'New Forum');
		}
		else {
			$form->bind($request);
			$data = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($data);
			$em->flush();
			return Core::redirect($this, 'homepage', null, 'Success!', 'The forum has been inserted successfully.');
		}

	}

	/**
     * @Route("/admin/category/new", name="newCategory")
     */
	public function newCategory(Request $request)
	{
		$category = new Category();
		$form = $this->createForm(new CategoryType(), $category, array(
				'action' => $this->generateUrl('newCategory')
				));
		if(!$request->isMethod('POST'))
		{
			return Core::doForm($this, $form, 'New Category');
		}
		else{
			$form->bind($request);
			$data = $form->getData();
			$em = $this->getDoctrine()->getManager();
			$em->persist($data);
			$em->flush();
			return Core::redirect($this, 'homepage', null, 'Success!', 'The category has been inserted successfully.');
		}
	}
}

?>