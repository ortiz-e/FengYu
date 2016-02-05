<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Model;
use AppBundle\Model\Core;
use AppBundle\Entity\Blog;
use AppBundle\Entity\Choice;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Vote;
use AppBundle\Form\Type\ThreadType;
use AppBundle\Form\Type\CommentType;
use AppBundle\Form\Type\PollType;

class BlogController extends Controller
{
    public $slug;
    const CAN_READ = 0;
    const CAN_REPLY = 1;
    const CAN_MAKE_THREAD = 2;
    const CAN_STICKY = 3;
    const CAN_CLOSE = 3;
    const CAN_EDIT_POST = 4;
    const CAN_EDIT_THREAD = 4;
    const CAN_ANNOUNCE = 5;

    /**
     * @Route("/forum/{slug}", name="forumView")
     */
    public function viewAll($slug)
    {
        $forum = $this->blogList($slug);
        $this->slug = $slug;

        return $this->render(
            Core::themePath($this) . '/default/forumView.html.twig',
            array(
                'forum' => $forum, 
                'globals' => Core::getGlobals($this)
                )
            );
    }

    /**
     * @Route("/thread/{slug}", name="threadView")
     */
    public function viewThread($slug)
    {

        //get the thread we're viewing
        $thread = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findOneBySlug($slug);

        $this->slug = $slug;

        //set some defaults
        $canIVote = false;
        $myChoice = 0;
        $totalVotes = 0;
        $canIRate = false;
        $threadRating = 0;

        if(!Core::hasForumPermission($this, $thread->getForum()->getSlug(), self::CAN_READ))
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You must be logged in to be able to read threads or posts. You will now be redirected to the previous page.');

        //can I vote?
        if($thread->getPoll() and Core::loggedIn($this))
        {
            $vote = $this->getDoctrine()
                ->getRepository('AppBundle:Vote')
                ->findOneBy(array('poll' => $thread->getPoll()->getId(), 'voter' => $this->getUser()->getId()));

            //I either can vote, or have a choice for which I already voted
            if(!$vote) $canIVote = true;
            else $myChoice = $vote->getChoice()->getId();
        }

        //if there's a poll, how many votes are there
        if($thread->getPoll())
        {
            $allVotes = $this->getDoctrine()
                ->getRepository('AppBundle:Vote')
                ->findByPoll($thread->getPoll()->getId());
            $totalVotes = count($allVotes);
        }

        /*
         * @TODO Make the filtering happen on post submit, not on post render
         */
        //we filter out the HTML from the main thread post
        $thread->setPost(Core::filterHTML($thread->getPost()));

        //then we iterate the thread's other posts by reference
        foreach($thread->getComments() as &$comment)
        {
            //and filter their HTML as well
            $comment->setText(Core::filterHTML($comment->getText()));
        }

        //I can only rate if I haven't already done so
        if(Core::loggedIn($this))
        {
            $rating = $this->getDoctrine()
                    ->getRepository('AppBundle:Vote')
                    ->findOneBy(array('blog' => $thread, 'voter' => $this->getUser()->getId()));
            if(!$rating) $canIRate = true;
        }
        
        //we get the thread's current rating
        $threadRating = $this->threadRating($thread);

        return $this->render(
            Core::themePath($this) . '/default/threadView.html.twig', 
            array(
                'thread' => $thread, 
                'canIVote' => $canIVote, 
                'myChoice' => $myChoice, 
                'totalVotes' => $totalVotes, 
                'rating' => $threadRating, 
                'canIRate' => $canIRate, 
                'globals' => Core::getGlobals($this))
            );
    }

    /**
     * Get the thread's rating
     *
     * @param integer $thread
     * @return integer threadRating
     */
    public function threadRating($thread)
    {
        $finalRating = $this->getDoctrine()
                    ->getRepository('AppBundle:Vote')
                    ->findByBlog($thread);
        $count = 0;
        $value = 0;
        foreach($finalRating as $rating)
        {
            //here we're adding the rating # for all the votes for this thread
            $value += $rating->getVoteValue();
            $count++;
        }

        //if zero return zero, else return the MATHS
        return !$count ? 0 : round($value / $count);
    }

    /**
     * Get the forum
     *
     * @param string $slug
     * @return \Entity\Forum
     */
    public function blogList($slug)
    {
        $forums = $this->getDoctrine()
            ->getRepository('AppBundle:Forum')
            ->findOneBySlug($slug);
        return $forums;
    }

    /**
     * @Route("/forum/{slug}/new", name="newThread")
     */
    public function newThread(Request $request, $slug)
    {
        //must be logged in to make a new thread
        if(!Core::loggedIn($this)) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You must be logged in to be able to make threads or post. You will now be redirected to the previous page.');

        //make sure the forum exists
        $forum = $this->blogList($slug);
        if(!$forum) 
            return Core::redirect(
                $this, 
                'referer', null, 'Error!', 'Could not find the forum. Returning you to the previous page.');

        if(!Core::hasForumPermission($this, $slug, self::CAN_READ))
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You do not have sufficient permissions to perform that action. You will now be redirected to the previous page.');

        //create new empty Blog (thread) entity
        $thread = new Blog();

        //create the form
        $form = $this->createForm(
            new ThreadType(), 
            $thread, 
            array(
            'action' => $this->generateUrl('newThread', array('slug' => $slug))
            ));

        //print form if no POST date else persist
        if(!$request->isMethod('POST'))
        {
            return Core::doForm($this, $form, 'New Thread');
        }
        else 
        {
            //bind form data
            $form->bind($request);
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            //generate a slug from the thread title
            $tSlug = Core::filter($data->getTitle());

            //our base slug is 'acceptable'
            $acceptable = $tSlug;

            //we start adding numbers at the end of it for every thread with the same slug
            $i = 2;

            //keep looking until we don'find threads with the 'acceptable slug'
            while($this->getDoctrine()->getRepository('AppBundle:Blog')->findOneBySlug($acceptable))
            {
                //since we found one, change the acceptable by adding i to the end of it
                $acceptable = $tSlug . "$i";
                $i++;

                //we're not doing this forever, poke user to change thread title if we can't find one yet
                if($i > 5) 
                    return Core::redirect(
                        $this, 
                        'referer', 
                        null, 
                        'Error!', 
                        'This thread title is too common. Try to be more descriptive. Returning you to the previous page.');
            }

            //finally the acceptable slug is acceptable
            $tSlug = $acceptable;
            $data->setSlug($tSlug);

            //set meta data and persist
            $data->setPublishDate(new \DateTime());
            $data->setAuthor($this->get('security.token_storage')->getToken()->getUser());
            $data->setForum($forum);
            $em->persist($data);
            $em->flush();

            return Core::redirect(
                $this, 
                'threadView', 
                array('slug' => $tSlug), 
                'Success!', 
                'The thread has been created successfully.');
        }
    }

    /**
     * @Route("/thread/{slug}/edit", name="editThread")
     */
    public function editThread(Request $request, $slug)
    {
        //gotta be logged in to edit threads
        if(!Core::loggedIn($this)) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You must be logged in to be able to edit threads. You will now be redirected to the previous page.');

        //make sure the thread exists
        $thread = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findOneBySlug($slug);
        if(!$thread) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'Could not find the thread. Returning you to the previous page.');

        //to edit a thread we must either be admin or be the thread's author
        if($thread->getAuthor()->getId() != $this->getUser()->getId() && !Core::isAdmin($this)) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You cannot edit other people\'s threads. Returning you to the previous page.');

        //create the form
        $form = $this->createForm(
            new ThreadType(), 
            $thread, //we use the thread we found instead of an empty thread
            array(
            'action' => $this->generateUrl('editThread', array('slug' => $slug))
            ));

        //print form if no POST data
        if(!$request->isMethod('POST'))
        {
            return Core::doForm($this, $form, 'Edit Thread');
        }
        else 
        {
            //persist everything
            $form->bind($request);
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            return Core::redirect(
                $this, 
                'threadView', 
                array('slug' => $slug), 
                'Success!', 
                'The thread has been edited successfully.');
        }
    }

    /**
     * @Route("/thread/{slug}/new/{quote}", name="newComment", defaults={"quote" : 0})
     */
    public function newReply(Request $request, $slug, $quote)
    {
        //are we logged in?
        if(!Core::loggedIn($this)) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You must be logged in to be able to make threads or post. You will now be redirected to the previous page.');

        //does the thread exist?
        $thread = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findOneBySlug($slug);
        if(!$thread) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'Could not find the thread. Returning you to the previous page.');

        //create the new Post from an empty entity
        $comment = new Comment();

        //if $quote > 0 it == quoted post ID
        if($quote != 0)
        {

            //we look for that post
            $quotedPost = $this->getDoctrine()
                ->getRepository('AppBundle:Comment')
                ->findOneById($quote);

            //if it exists
            if($quotedPost)
            {
              //we take that originally empty entity and add text and a title to it based on the quoted post
              $comment->setText("<blockquote><cite>Originally posted by " . Core::userLink($this, $quotedPost->getAuthor()) ."</cite>". $quotedPost->getText() ."</blockquote>"); 
              $comment->setTitle("RE: " . str_replace("RE: ", "", $quotedPost->getTitle()));  
            } 
        }
        else $comment->setTitle('RE: ' . $thread->getTitle());

        //create the form
        $form = $this->createForm(
            new CommentType(), 
            $comment, 
            array(
            'action' => $this->generateUrl('newComment', array('slug' => $slug))
            ));

        //print the form if no POST data else
        if(!$request->isMethod('POST'))
        {
            return Core::doForm($this, $form, 'New Reply');
        }
        else 
        {
            $form->bind($request);
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            //set meta data
            $data->setPublishDate(new \DateTime());
            $data->setAuthor($this->get('security.token_storage')->getToken()->getUser());
            $data->setBlog($thread);

            //and persist
            $em->persist($data);
            $em->flush();

            return Core::redirect(
                $this, 
                'threadView', 
                array('slug' => $slug), 
                'Success!', 
                'The post has been created successfully. You are now being redirected to the thread.');
        }
    }

    /**
     * @Route("/thread/{slug}/poll", name="newPoll")
     */
    public function newPoll(Request $request, $slug)
    {
        //we have to be logged in to make a new poll
        if(!Core::loggedIn($this)) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You must be logged in to be able to make threads or post. You will now be redirected to the previous page.');

        //let's get the thread we're adding a poll to
        $thread = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findOneBySlug($slug);

        //it must exist
        if(!$thread) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'Could not find the thread. Returning you to the previous page.');

        //the logged in user must have created this thread, unless he's an admin
        if($thread->getAuthor()->getId() != $this->getUser()->getId() && !Core::isAdmin($this)) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You cannot edit other people\'s threads. Returning you to the previous page.');

        //the thread must not already have a poll
        if($thread->getPoll()) 
            return Core::redirect(
                $this, 
                'referer',
                null, 
                'Error!', 
                'This thread already has a poll associated with it. Returning you to the previous page.');

        //let's create that form
        $form = $this->createForm(
            new PollType(), 
            $thread->getPoll(), 
            array(
            'action' => $this->generateUrl('newPoll', array('slug' => $slug))
            ));

        //if there is no POST data
        if(!$request->isMethod('POST'))
        {
            //return the form
            return Core::doForm($this, $form, 'Thread Poll');
        }
        else 
        {
            //first we get the poll data from the REQUEST data
            $poll = $request->request->get('poll');

            //then we get the poll choices data isolated (NOTE: not by reference...)
            $choices = $poll['choices'];

            //...because we're going to remove the choices from the poll data
            unset($poll['choices']);

            //and overwrite the poll data in the request with the data lacking the choices
            $request->request->set('poll', $poll);

            //we bind the request
            $form->bind($request);

            //we get the entity and generate the Doctrine manager
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            //we set the thread and author data for the Poll entity
            $data->setBlog($thread);
            $data->setAuthor($this->getUser());

            //then we persist and insert
            $em->persist($data);
            $em->flush();

            //we now get the Entity ID
            $id = $data->getId();

            //and use it to delete existing choices for that poll ID (future use: poll editing)
            $query = $em->createQuery('DELETE AppBundle:Choice c WHERE c.poll = :id');
            $query->setParameter('id', $id);
            $query->execute();

            //now we get that poll we just inserted
            $poll = $this->getDoctrine()
                 ->getRepository('AppBundle:Poll')
                 ->findOneById($id);

            //we iterate the choices we isolated way back
            foreach($choices as $choice)
            {

                //and persist them individually
                $newchoice = new Choice();
                $newchoice->setTitle($choice);
                $newchoice->setPoll($poll);
                $poll->addChoice($newchoice); //while also adding them to the poll
                $em->persist($newchoice);
            }

            //and finally, we persist the poll again
            $em->persist($poll);
            $em->flush();
            
            return Core::redirect(
                $this, 
                'threadView', 
                array('slug' => $slug), 
                'Success!', 
                'The poll has been created successfully. You are now being redirected to the thread.');
        }

    }

    /**
     * @Route("/thread/{slug}/poll/vote/{id}", name="newVote")
     */
    public function newVote($slug, $id)
    {
        //need to be logged in to vote
        if(!Core::loggedIn($this))
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'You must be logged in to be able to vote. You will now be redirected to the previous page.');

        //we make sure this is a valid thread
        $thread = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findOneBySlug($slug);

        //return an error otherwise
        if(!$thread) 
            return Core::redirect(
                $this, 
                'referer',
                 null, 
                 'Error!', 
                 'Could not find the thread. Returning you to the previous page.');

        //this thread we're voting in must have a poll
        if(!$thread->getPoll()) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'This thread has no poll associated with it. Returning you to the previous page.');

        //we make sure we haven't already voted on this poll
        $vote = $this->getDoctrine()
                ->getRepository('AppBundle:Vote')
                ->findOneBy(array(
                    'poll' => $thread->getPoll()->getId(), 
                    'voter' => $this->getUser()->getId()
                    ));
        if($vote) 
            return Core::redirect(
                $this, 
                'referer',
                null, 
                'Error!', 
                'You already voted once on this poll! Returning you to the previous page.');

        //make sure the choice we're voting for actually exists
        $choice = $this->getDoctrine()
                ->getRepository('AppBundle:Choice')
                ->findOneById($id);
        if(!$choice) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'An unexpected error has occurred. Returning you to the previous page.');

        //make sure we're not messing with things here...
        if($choice->getPoll() != $thread->getPoll()) 
            return Core::redirect(
                $this, 
                'referer', 
                null, 
                'Error!', 
                'An unexpected error has occurred. Returning you to the previous page.');

        //finally, create my new Vote
        $myVote = new Vote();
        $myVote->setVoter($this->getUser())
               ->setPoll($thread->getPoll())
               ->setChoice($choice)
               ->setVoteValue(1); //this is always 1 unless the vote is a thread rating

        //persist vote and send back
        $em = $this->getDoctrine()->getManager();
        $em->persist($myVote);
        $em->flush();
        return Core::redirect(
            $this, 
            'threadView', 
            array('slug' => $slug), 
            'Success!', 
            'Your vote has been recorded successfully. You are now being redirected to the thread.');
    }

    /**
     * @Route("/thread/{slug}/rate", name="newRating")
     * @Method("POST")
     */
    public function newRating($slug)
    {
        //first we get the JSON POST request sent via AJAX
        $request = $this->get('request');
        $rating = $request->get('rating');

        //we prepare a stock error response to reuse 
        $error = json_encode(array('code' => 110, 'success' => false));

        //we send an error response if not logged in
        if(!Core::loggedIn($this)) return new Response($error);

        //we make sure the thread exists
        $thread = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findOneBySlug($slug);
        if(!$thread) return new Response($error);

        //we make sure we haven't voted (voter) for this thread (blog)
        $vote = $this->getDoctrine()
                ->getRepository('AppBundle:Vote')
                ->findOneBy(array('blog' => $thread->getId(), 'voter' => $this->getUser()->getId()));
        if($vote) return new Response($error);

        //we make sure this is a valid rating from 1 to 5
        if(!$rating || $rating > 5 || $rating < 1 || !is_numeric($rating)) return new Response($error);

        //new Vote entity with logged user as voter, and POST data for other fields
        $myVote = new Vote();
        $myVote->setVoter($this->getUser())
               ->setBlog($thread)
               ->setVoteValue($rating);

        //persist vote
        $em = $this->getDoctrine()->getManager();
        $em->persist($myVote);
        $em->flush();

        //we send a new response, but first we calculate the thread's 
        //new rating so that we can update the HTML accordingly
        $threadRating = $this->threadRating($thread);
        return new Response(json_encode(array('code' => 100, 'success' => true, 'rating' => $threadRating)));
    }
}
