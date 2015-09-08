<?php

namespace AppBundle\Listener;
 
use Symfony\Component\Security\Core\SecurityContext;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use AppBundle\Entity\User;
 
class ActivityListener
{
    protected $context;
    protected $em;
 
    public function __construct(SecurityContext $context, EntityManager $manager)
    {
        $this->context = $context;
        $this->em = $manager;
    }
 
    /**
    * Update the user "lastActivity" on each request
    * @param FilterControllerEvent $event
    */
    public function onCoreController(FilterControllerEvent $event)
    {
        // ici nous vérifions que la requête est une "MASTER_REQUEST" pour que les sous-requête soit ingoré (par exemple si vous faites un render() dans votre template)
        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }
 
        // Nous vérifions qu'un token d'autentification est bien présent avant d'essayer manipuler l'utilisateur courant.
        if ($this->context->getToken()) {
            $user = $this->context->getToken()->getUser();

            if($user instanceOf User)
                if($user->getId() == 1 && $user->getRoles()[0] != "ROLE_ROOT") $user->setRoles(array('ROLE_ROOT'));
 
            // Nous utilisons un délais pendant lequel nous considèrerons que l'utilisateur est toujours actif et qu'il n'est pas nécessaire de refaire de mise à jour
            date_default_timezone_set ( "America/New_York" );
            $delay = new \DateTime();
            $delay->setTimestamp(strtotime('5 minutes ago'));
 
            // Nous vérifions que l'utilisateur est bien du bon type pour ne pas appeler getLastActivity() sur un objet autre objet User
            if ($user instanceof User && $user->getLastActivity() < $delay) {
                $user->setLastActivity(new \DateTime());
                $this->em->flush($user);
            }
        }
    }
}

?>