<?php

namespace App\AdminBundle\Controller;

class UserController extends AdminBaseController {

    protected $_name = 'User';

    public function indexAction() {
        $users = $this->paginator($this->_name);
        return $this->renderTpl($this->_name . ':index', compact('users'));
    }

    public function addAction() {
	
	$user = $this->getEntity($this->_name);

	$form = $this->getForm($this->_name);
	$form->setData($user);

	$req_user = $this->get('request');
	if ($req_user->getMethod() == 'POST') {
		$form->bindRequest($req_user);

		/**
		 * Return User encode password
		 */
		$password = $this->getEncodePassword($user);
		$user->setPassword($password);

		$em = $this->getEm();
		foreach ($user->getTeam() as $team) {
			$team->getUser()->add($user);

			$em->persist($user);
			$em->persist($team);
			$em->flush();
		}
		$this->flash('User add');
		return $this->myRedirect('_admin_user_index');
	}
	
	$form = $form->createView();
	return $this->renderTpl($this->_name . ':add', compact('form'));
    }

    public function editAction($id) {
	
	$user = $this->findOne($this->_name, $id);
	$form = $this->getForm($this->_name, $user);

	$req_user = $this->get('request');
	if ($req_user->getMethod() == 'POST') {
		$form->bindRequest($req_user); 
		/**
		 * Return User encode password
		 */
		$password = $this->getEncodePassword($user);
		$user->setPassword($password);

		$em = $this->getEm();

		// delete user's team
		foreach (current($user->getTeam()) as $team) {
			$user->getTeam()->removeElement($team);
			$team->getUser()->removeElement($user);

			$em->persist($user);
			$em->persist($team);
			$em->flush();
		}

		// Insert user's team
		$user_data = $req_user->get('user');
                $teams = $user_data['team'];
		foreach ($teams as $team_id) {
                        $team = $this->findOne('Team', $team_id);
			$team->getUser()->add($user);

			$em->persist($user);
			$em->persist($team);
			$em->flush();
		}
		$this->flash('User edited');
		return $this->myRedirect('_admin_user_index');
	}
	
	$form = $form->createView();
	return $this->renderTpl($this->_name . ':add', compact('form'));
    }

    public function deleteAction($id) {
        $securityContext = $this->container->get('security.context');

        // check for edit access
        if (!$securityContext->isGranted('ROLE_ADMIN')) {
            throw new \Symfony\Component\Security\Core\Exception\AccessDeniedException();
        }
        return parent::deleteAction($id);
    }
}

