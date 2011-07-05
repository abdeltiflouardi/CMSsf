<?php

namespace App\CoreBundle\EventListener;

use  Symfony\Component\HttpKernel\Event\FilterResponseEvent,
     Symfony\Bundle\TwigBundle\TwigEngine;

class AdsenseHookListener {

    protected $templating;
    protected $height;
    protected $width;
    protected $clientId;
    protected $colors;

    public function __construct(TwigEngine $templating, $client_id, $height, $width, $colors) {
        $this->templating  = $templating;
        $this->clientId    = $client_id;
        $this->height      = $height;
        $this->width       = $width;
        $this->colors      = $colors;
    }

    public function getClientId() { 
        return $this->clientId; 
    }

    public function getHeight() {
        return $this->height;
    }

    public function getWidth() {
       return $this->width;
    }

    public function getColors() {
       return $this->colors;
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        $response = $event->getResponse();

        if (function_exists('mb_stripos')) {
            $posrFunction = 'mb_strripos';
        } else {
            $posrFunction = 'strripos';
        }

        $content = $response->getContent();

        if (false !== $pos = $posrFunction($content, '<!-- HOOK SIDEBAR -->')) {
            $sidebar = $this->templating->render('AppCoreBundle:Hook:adsense.html.twig', array('vars' => $this));
            $content = preg_replace('/<!-- HOOK SIDEBAR -->/', $sidebar, $content); 
            $response->setContent($content);
        }
    }

}
