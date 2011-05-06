<?php

namespace App\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller {

    protected $_namespace = null;
    protected $_commonNamespace = 'AppCoreBundle:';
    protected $_commonNamespacePath = 'App\CoreBundle';
    protected $_tplEngine = '.html.twig';

    public function setNamespace($namespace) {
        $this->_namespace = $namespace;
    }

    public function getNamespace() {
        return $this->_namespace;
    }

    public function setTplEngine($tplEngine) {
        $this->_tplEngine = $tplEngine;
    }

    public function getTplEngine() {
        return $this->_tplEngine;
    }

    public function getEm() {
        return $this->get('doctrine.orm.entity_manager');
    }

    public function getRepo($entity) {
        return $this->getEm()->getRepository($this->_commonNamespace . $entity);
    }

    public function paginator($entity, $options = array()) {
        
        $_default_options = array(
          'itemPerPage' => 5,
          'pageRange' => 5
        );
        
        $options = array_merge($_default_options, $options);
        
        $dql = "SELECT a FROM " . $this->_commonNamespace . $entity . " a";        
        
        $query = $this->getEm()->createQuery($dql);

        $adapter = $this->get('knplabs_paginator.adapter');
        $adapter->setQuery($query);
        $adapter->setDistinct(true);

        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->get('request')->query->get('page', 1));
        $paginator->setItemCountPerPage($options['itemPerPage']);
        $paginator->setPageRange($options['pageRange']);
        
        return $paginator;
    }

    public function renderTpl($action, $params = array()) {
        return $this->render($this->getNamespace() . $action . $this->getTplEngine(), $params);
    }

    public function getAll($entity) {
        return $this->getRepo($entity)->findAll();
    }

    public function findOne($entity, $id) {
        return $this->getEm()->find($this->_commonNamespace . $entity, $id);
    }

    public function removeOne($entity, $id) {
        $item = $this->findOne($entity, $id);
        $em = $this->getEm();
        $em->remove($item);
        $em->flush();
    }

    public function addItem($entity, array $options = array()) {
        $form = $this->get('form.factory')->create($this->getType($entity));

        $$entity = $this->getEntity($entity);
        $form->setData($$entity);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if (method_exists($$entity, 'setCreatedAt')) {
                $$entity->setCreatedAt(new \DateTime);
            }

            if (method_exists($$entity, 'setUpdatedAt')) {
                $$entity->setUpdatedAt(new \DateTime);
            }

            if ($form->isValid()) {

                if (isset($options['afterValid']))
                    foreach ($options['afterValid'] as $method => $value)
                        $$entity->$method($value);

                $em = $this->getEm();
                $em->persist($$entity);
                $em->flush();
                return $this->redirect($this->generateUrl('_admin_' . strtolower($entity) . '_index'));
            }
        }

        $form = $form->createView();
        return $this->renderTpl($entity . ':add', compact('form'));
    }

    public function editItem($entity, $id, array $options = array()) {
        $form = $this->get('form.factory')->create($this->getType($entity));

        $$entity = $this->findOne($entity, $id);
        $form->setData($$entity);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if (method_exists($$entity, 'setUpdatedAt')) {
                $$entity->setUpdatedAt(new \DateTime);
            }

            if ($form->isValid()) {

                if (isset($options['afterValid']))
                    foreach ($options['afterValid'] as $method => $value)
                        $$entity->$method($value);

                $em = $this->getEm();
                $em->persist($$entity);
                $em->flush();
                return $this->redirect($this->generateUrl('_admin_' . strtolower($entity) . '_index'));
            }
        }

        $form = $form->createView();
        return $this->renderTpl($entity . ':edit', compact('form'));
    }

    public function removeItem($entity, $id) {
        $this->removeOne($entity, $id);
        return $this->redirect($this->generateUrl('_admin_' . strtolower($entity) . '_index'));
    }

    public function getType($entity) {
        $type = $this->_commonNamespacePath . '\Type\\' . $entity;
        return new $type;
    }

    public function getEntity($entity) {
        $type = $this->_commonNamespacePath . '\Entity\\' . $entity;
        return new $type;
    }

    public function getForm($entity) {
        return $this->get('form.factory')->create($this->getType($entity));
    }

    public function getEncodePassword($user = null) {
        $encoders = $this->get('security.encoder_factory');
        $encoder = $encoders->getEncoder($user);

        return $encoder->encodePassword($user->getPassword(), $user->getSalt());
    }

}
