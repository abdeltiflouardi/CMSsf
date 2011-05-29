<?php

namespace App\CoreBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\FileLocator;

class AppCoreExtension extends Extension {

     public function load(array $config, ContainerBuilder $container) {
          $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        foreach (array('twig') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }          
     }
}
