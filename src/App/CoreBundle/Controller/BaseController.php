<?php

namespace App\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\Security\Acl\Permission\MaskBuilder,
    Symfony\Component\Security\Acl\Domain\ObjectIdentity,
    Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;

class BaseController extends Controller {

    protected $_namespace = 'AppCoreBundle:';
    protected $_commonNamespace = 'AppCoreBundle:';
    protected $_commonNamespacePath = 'App\CoreBundle';
    protected $_tplEngine = '.html.twig';
    protected $_data = array();

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
        return $this->get('doctrine')->getEntityManager();
    }

    public function getRepo($entity) {
        return $this->getEm()->getRepository($this->_commonNamespace . $entity);
    }

    public function paginator($entity, $options = array()) {
        
	$entity = isset($options['entity']) ? $options['entity'] : $entity;

        $_default_options = array(
          'itemPerPage' => 5,
          'pageRange' => 5
        );
        
        $options = array_merge($_default_options, $options);     
 
        $dql = "SELECT a FROM " . $this->_commonNamespace . $entity . " a";        
        
        if (isset($options['where']))
            $dql .= ' WHERE ' . $options['where'];

        if (isset($options['order']))
            $dql .= ' ORDER BY ' . $options['order'];
        
        $query = $this->getEm()->createQuery($dql);

        $adapter = $this->get('knplabs_paginator.adapter');
        $adapter->setQuery($query);
        $adapter->setDistinct(true);

        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($this->get('request')->query->get('page', 1));
        $paginator->setPageRange($options['pageRange']);
        $paginator->setItemCountPerPage($options['itemPerPage']);
       
        return $paginator;
    }

    public function renderData(array $data = array()) {
        $this->_data += $data;
        return $this;
    }

    public function getData() {
        return $this->_data;
    }

    public function renderTpl($action, $params = array(), $common = false) {
        if ($common === false)
            $ns = $this->getNamespace();
        else
            $ns = $this->_commonNamespace;
        
        $this->renderData($params);

        return $this->render($ns . $action . $this->getTplEngine(), $this->getData());
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

            if ($form->isValid()) {

                if (isset($options['afterValid']))
                    foreach ($options['afterValid'] as $method => $value)
                        	$$entity->$method($value);
		
                $em = $this->getEm();
                $em->persist($$entity);
                $em->flush();

                //Insert Tags
                $this->get('tags')->addTags($$entity->getId());
		
		// Session flash
		$this->flash(strtolower($entity) . ' added');
		
		//Redirect
                return $this->redirect($this->generateUrl('_admin_' . strtolower($entity) . '_index'));
            }
        }

        $form = $form->createView();
        return $this->renderTpl($entity . ':add', compact('form'));
    }

    public function editItem($entity, $id, array $options = array()) {
        $form = $this->get('form.factory')->create($this->getType($entity));

        $$entity = $this->findOne($entity, $id);
        
	if (!$$entity)
		return $this->renderTpl ('Error:error', ErrorController::error (404), true);

        $form->setData($$entity);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {

                if (isset($options['afterValid']))
                    foreach ($options['afterValid'] as $method => $value)
                        $$entity->$method($value);

                $em = $this->getEm();
                $em->persist($$entity);
                $em->flush();
		
		//Edit Tags
		$this->get('tags')->editTags($$entity->getId());

		// Session flash
		$this->flash(strtolower($entity) . ' edited');

		//Redirect
                return $this->redirect($this->generateUrl('_admin_' . strtolower($entity) . '_index'));
            }
        }

        $form = $form->createView();
        return $this->renderTpl($entity . ':edit', compact('form'));
    }

    public function removeItem($entity, $id) {
        $this->removeOne($entity, $id);

	// Redirect
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

    public function getForm($entity, $param = null) {
        return $this->get('form.factory')->create($this->getType($entity), $param);
    }

    public function getEncodePassword($user = null) {
        $encoders = $this->get('security.encoder_factory');
        $encoder = $encoders->getEncoder($user);

        return $encoder->encodePassword($user->getPassword(), $user->getSalt());
    }

    public function makeAcl($entity, $mask = MaskBuilder::MASK_OWNER) {
        // creating the ACL
        $aclProvider = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $acl = $aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $securityIdentity = UserSecurityIdentity::fromAccount($this->getUser());

        // grant owner access
        $acl->insertObjectAce($securityIdentity, $mask);
        $aclProvider->updateAcl($acl);
    }

    public function trans($text) {
        return $this->get('translator')->trans($text);
    }

    public function flash($message, $type = "message") {
        $this->get('session')->setFlash($type, $this->trans($message));
    }

    public function myRedirect($router) {
	return $this->redirect($this->generateUrl($router));
    }

    public function getUser() {
        $token = $this->get('security.context')->getToken();
        
        return $token->getUser();
    }
    
    public function notFound($message = null, $common = true) {
        return $this->renderTpl ('Error:error', ErrorController::error (404, $message), $common);        
    }

    public function AccessDenied($message = null, $common = true) {
        return $this->renderTpl ('Error:error', ErrorController::error (405, $message), $common);
    }

}
