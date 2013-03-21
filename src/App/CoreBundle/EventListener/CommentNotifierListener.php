<?php

namespace App\CoreBundle\EventListener;

use  Symfony\Component\HttpKernel\Event\FilterResponseEvent,
     Doctrine\Bundle\DoctrineBundle\Registry as Doctrine,
     Symfony\Bundle\TwigBundle\TwigEngine;

class CommentNotifierListener {

    protected $templating;
    protected $doctrine;
    protected $security;

    public function __construct(TwigEngine $templating, Doctrine $doctrine, $security) {
        $this->templating  = $templating;
        $this->doctrine = $doctrine;
        $this->security = $security;
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        $response = $event->getResponse();

        if (function_exists('mb_stripos')) {
            $posrFunction = 'mb_strripos';
        } else {
            $posrFunction = 'strripos';
        }

        $content = $response->getContent();

        if (false !== $pos = $posrFunction($content, '<!-- Comment notifier -->')) {

            $user = $this->security->getToken()->getUser();
            // @TODO count instead of select *
            $query = $this->doctrine->getEntityManager()
                          ->createQuery('
                                     SELECT c, p 
                                     FROM AppCoreBundle:Comment c JOIN c.post p
                                     WHERE c.viewed = 0 AND p.user = :user
                                    ')
                          ->setParameter('user', $user);

            $count = count($query->getResult());
            $notify = $this->templating->render('AppCoreBundle:Hook:comment_notifier.html.twig', compact('count'));
            $content = preg_replace('/<!-- Comment notifier -->/', $notify, $content); 
            $response->setContent($content);
        }
    }

}
