<?php

namespace App\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;

class BaseController extends Controller
{

    protected $namespace           = 'AppCoreBundle:';
    protected $commonNamespace     = 'AppCoreBundle:';
    protected $commonNamespacePath = 'App\CoreBundle';
    protected $tplEngine           = '.html.twig';
    protected $data                = array();

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setTplEngine($tplEngine)
    {
        $this->tplEngine = $tplEngine;
    }

    public function getTplEngine()
    {
        return $this->tplEngine;
    }

    public function getEm()
    {
        return $this->get('doctrine')->getEntityManager();
    }

    public function getRepo($entity)
    {
        return $this->getEm()->getRepository($this->commonNamespace . $entity);
    }

    public function paginator($entity, $options = array())
    {

        $_default_options = array(
            'itemPerPage' => 5,
            'pageRange'   => 5
        );

        $options = array_merge($_default_options, $options);

        if ($entity instanceof \Doctrine\ORM\Query) {
            $query = $entity;
        } else {
            $entity = isset($options['entity']) ? $options['entity'] : $entity;

            $dql = "SELECT a FROM " . $this->commonNamespace . $entity . " a";

            if (isset($options['where'])) {
                $dql .= ' WHERE ' . $options['where'];
            }

            if (isset($options['order'])) {
                $dql .= ' ORDER BY ' . $options['order'];
            }

            $query = $this->getEm()->createQuery($dql);
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/* page number */,
            $options['itemPerPage']/* limit per page */
        );

        return $pagination;
    }

    public function renderData(array $data = array())
    {
        $this->data += $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function renderTpl($action, $params = array(), $common = false)
    {
        if ($common === false) {
            $nsp = $this->getNamespace();
        } else {
            $nsp = $this->commonNamespace;
        }

        $this->renderData($params);

        return $this->render($nsp . $action . $this->getTplEngine(), $this->getData());
    }

    public function getAll($entity)
    {
        return $this->getRepo($entity)->findAll();
    }

    public function findOne($entity, $entityId)
    {
        return $this->getEm()->find($this->commonNamespace . $entity, $entityId);
    }

    public function removeOne($entity, $entityId)
    {
        $item = $this->findOne($entity, $entityId);

        $this->getEm()->remove($item);
        $this->getEm()->flush();
    }

    public function addItem($entity, array $options = array())
    {
        $form = $this->get('form.factory')->create($this->getType($entity));

        $$entity = $this->getEntity($entity);
        $form->setData($$entity);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {

                if (isset($options['afterValid'])) {
                    foreach ($options['afterValid'] as $method => $value) {
                        $$entity->$method($value);
                    }
                }

                $this->getEm()->persist($$entity);
                $this->getEm()->flush();

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

    public function editItem($entity, $entityId, array $options = array())
    {
        $form = $this->get('form.factory')->create($this->getType($entity));

        $$entity = $this->findOne($entity, $entityId);

        if (!$$entity) {
            return $this->renderTpl('Error:error', ErrorController::error(404), true);
        }

        $form->setData($$entity);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {

                if (isset($options['afterValid'])) {
                    foreach ($options['afterValid'] as $method => $value) {
                        $$entity->$method($value);
                    }
                }

                $this->getEm()->persist($$entity);
                $this->getEm()->flush();

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

    public function removeItem($entity, $entityId)
    {
        $this->removeOne($entity, $entityId);

        // Redirect
        return $this->redirect($this->generateUrl('_admin_' . strtolower($entity) . '_index'));
    }

    public function getType($entity)
    {
        $type = $this->commonNamespacePath . '\Type\\' . $entity;
        return new $type;
    }

    public function getEntity($entity)
    {
        $type = $this->commonNamespacePath . '\Entity\\' . $entity;
        return new $type;
    }

    public function getForm($entity, $param = null)
    {
        return $this->createForm($this->getType($entity), $param);
    }

    public function getEncodePassword($user = null)
    {
        $encoders = $this->get('security.encoder_factory');
        $encoder  = $encoders->getEncoder($user);

        return $encoder->encodePassword($user->getPassword(), $user->getSalt());
    }

    public function makeAcl($entity, $mask = MaskBuilder::MASK_OWNER)
    {
        // creating the ACL
        $aclProvider    = $this->get('security.acl.provider');
        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $acl            = $aclProvider->createAcl($objectIdentity);

        // retrieving the security identity of the currently logged-in user
        $securityIdentity = UserSecurityIdentity::fromAccount($this->getUser());

        // grant owner access
        $acl->insertObjectAce($securityIdentity, $mask);
        $aclProvider->updateAcl($acl);
    }

    public function trans($text)
    {
        return $this->get('translator')->trans($text);
    }

    public function flash($message, $type = "message")
    {
        $this->get('session')->setFlash($type, $this->trans($message));
    }

    public function myRedirect($router)
    {
        return $this->redirect($this->generateUrl($router));
    }

    public function getUser()
    {
        $token = $this->get('security.context')->getToken();

        return $token->getUser();
    }

    public function notFound($message = null, $common = true)
    {
        return $this->renderTpl('Error:error', ErrorController::error(404, $message), $common);
    }

    public function accessDenied($message = null, $common = true)
    {
        return $this->renderTpl('Error:error', ErrorController::error(405, $message), $common);
    }
}
