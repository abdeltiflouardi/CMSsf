<?php

namespace App\CoreBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\Definition\Processor,
    Symfony\Component\Config\FileLocator;

class AppCoreExtension extends Extension {

     public function load(array $configs, ContainerBuilder $container) {

        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        foreach (array('listener', 'twig', 'twig_extensions') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        } 

        foreach ($this->buildConfig($config) as $key => $value) {
            $container->setParameter($key, $value);
        }
     }

    public function buildConfig($item, $built = null, $parent = null) {
        foreach ($item as $key => $value) {
            if (is_array($value)) {
                $parent = $parent . '.' . $key;
                $built = $this->buildConfig($value, $built, $parent);
                $parent = null;
            } else {
                $tmp_parent = $parent . '.' . $key;
                $tmp_parent = ltrim($tmp_parent, '.');
                $built[$tmp_parent] = $value;
            }
        }

        return $built;
    }
}
