<?php

namespace App\WebBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext,
    App\CoreBundle\Request\ForgottenPassword,
    App\CoreBundle\Request\InitPassword;

class UserController extends WebBaseController
{

    protected $_name = 'User';

    public function signinAction()
    {
        /**
         * Signup
         */
        $user = $this->getEntity($this->_name);

        $form_signup = $this->getForm('Signup', $user);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form_signup->bindRequest($request);

            $team = $this->findOne('Team', 3);
            $user->addTeam($team);
            $team->addUser($user);

            if ($form_signup->isValid()) {
                //Encode password
                $user->setPassword($this->getEncodePassword($user));

                $em = $this->getEm();
                $em->persist($team);
                $em->persist($user);
                $em->flush();

                $token = $user->getEmail();

                $message = \Swift_Message::newInstance()
                        ->setContentType("text/html")
                        ->setSubject('Activation de votre compte')
                        ->setFrom('ouardisoft@localhost')
                        ->setTo($user->getEmail())
                        ->setBody($this->renderView('AppWebBundle:Mail:activate.html.twig', array('token' => $token)))
                ;
                $this->get('mailer')->send($message);

                return $this->redirect($this->generateUrl('_home'));
            }
        }
        $form_signup = $form_signup->createView();

        /**
         * Signin
         */
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error     = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }
        $last_name = $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME);

        $form_signin = $this->getForm('Signin')->setData(array('login' => $last_name))->createView();

        $this->renderData(compact('form_signin', 'form_signup', 'error', 'last_name'));
        return $this->renderTpl('User:signin');
    }

    public function forgottenPasswordAction()
    {
        $fp   = new ForgottenPassword();
        $form = $this->getForm('ForgottenPassword', $fp);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $user = $this->getRepo('User')->findOneByEmail($fp->getEmail());
                if (!$user)
                    $this->flash('Email not found');
                else {
                    $token = $user->getEmail();

                    $message = \Swift_Message::newInstance()
                            ->setContentType("text/html")
                            ->setSubject('Forgotten password')
                            ->setFrom('ouardisoft@server.lan')
                            ->setTo($user->getEmail())
                            ->setBody($this->renderView('AppWebBundle:Mail:forgotten_password.html.twig', array('token' => $token)))
                    ;

                    $this->get('mailer')->send($message);
                }
            }
        }

        $form = $form->createView();
        return $this->renderTpl($this->_name . ':forgotten_password', compact('form'));
    }

    public function initPasswordAction($token)
    {

        $user = $this->getRepo('User')->findOneByEmail($token);

        if (!$user)
            return $this->notFound('User not found', false);

        $init_password = new InitPassword();
        $form          = $this->getForm('InitPassword', $init_password);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $request_initpassword = $request->get('initpassword');
                $new_password         = current($request_initpassword['newPassword']);

                $password     = $user->getPassword();
                $user->setPassword($init_password->getOldPassword());
                $old_password = $this->getEncodePassword($user);

                if ($password != $old_password) {
                    $this->flash('Old password not valid');
                } else {
                    $user->setPassword($init_password->getNewPassword());
                    $new_password = $this->getEncodePassword($user);
                    $user->setPassword($new_password);
                    $em           = $this->getEm();
                    $em->persist($user);
                    $em->flush();

                    $this->flash('Password updated');
                    return $this->redirect($this->generateUrl('_init_password', array('token' => $token)));
                }
            }
        }

        $form = $form->createView();
        return $this->renderTpl($this->_name . ':init_password', compact('form'));
    }

    public function activateAction($token)
    {
        $user = $this->getRepo('User')->findOneByEmail($token);

        if (!$user)
            return $this->notFound('Account not exists', false);

        $user->setEnabled(1);

        $em = $this->getEm();
        $em->persist($user);
        $em->flush();

        $this->flash('Profile activated');
        return $this->renderTpl($this->_name . ':activate');
    }

    public function profileAction()
    {
        $user = $this->getUser();

        $form    = $this->getForm('Signup', $user);
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {

                $user->setPassword($this->getEncodePassword($user));

                $em = $this->getEm();
                $em->persist($user);
                $em->flush();

                $this->flash('Profile edited');
                return $this->redirect($this->generateUrl('_profile'));
            }
        }
        $form = $form->createView();

        return $this->renderTpl($this->_name . ':profile', compact('form'));
    }

    public function commentsAction()
    {
        $comments = $this->getUser()->getComments();

        return $this->renderTpl($this->_name . ':comments', compact('comments'));
    }

    public function usersCommentsAction()
    {
        $user = $this->getUser();

        $query = $this->getEM()
                ->createQuery('
                                     SELECT c, p 
                                     FROM AppCoreBundle:Comment c JOIN c.post p
                                     WHERE p.user = :user
                                    ')
                ->setParameter('user', $user);

        $comments = $this->paginator($query, array('itemPerPage' => 1));

        return $this->renderTpl($this->_name . ':users_comments', compact('comments'));
    }

    public function commentEditAction($comment_id)
    {
        $comment = $this->findOne('Comment', $comment_id);

        if (!$comment)
            return $this->notFound('Comment not found', false);

        if (!$this->get('security.context')->isGranted('EDIT', $comment) &&
                !$this->get('security.context')->isGranted('ROLE_MODERATE'))
            return $this->accessDenied(null, false);

        $form = $this->getForm('Comment', $comment);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEm();
                $em->persist($comment);
                $em->flush();

                $this->flash('Comment edited');
                return $this->myRedirect('_comments');
            }
        }

        $form = $form->createView();

        return $this->renderTpl($this->_name . ':comment_edit', compact('form'));
    }

    public function commentDeleteAction($comment_id)
    {

        $comment = $this->findOne('Comment', $comment_id);

        if (!$comment)
            return $this->notFound(sprintf('Comment #%s not found', $comment_id), false);

        if (!$this->get('security.context')->isGranted('DELETE', $comment) &&
                !$this->get('security.context')->isGranted('ROLE_MODERATE'))
            return $this->accessDenied(null, false);

        if ($confirm = $this->get('request')->query->get('confirm')) {
            $em = $this->getEm();
            $em->remove($comment);
            $em->flush();

            $this->flash('Commentaire deleted');
            return $this->myRedirect('_comments');
        }

        return $this->renderTpl($this->_name . ':comment_delete', compact('comment'));
    }

    public function postsAction()
    {
        $posts = $this->getUser()->getPosts();

        return $this->renderTpl($this->_name . ':posts', compact('posts'));
    }

    public function postEditAction($post_id)
    {
        $post = $this->findOne('Post', $post_id);

        if (!$post)
            return $this->notFound('Post not found', false);

        if (!$this->get('security.context')->isGranted('EDIT', $post) &&
                !$this->get('security.context')->isGranted('ROLE_MODERATE'))
            return $this->accessDenied(null, false);

        $form = $this->getForm('Post', $post);
        $form->remove('user');

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->getEm();
                $em->persist($post);
                $em->flush();
                $this->get('tags')->editTags($post_id);

                $this->flash('Post edited');
                return $this->myRedirect('_posts');
            }
        }

        $form = $form->createView();

        return $this->renderTpl($this->_name . ':post_edit', compact('form'));
    }

    public function postDeleteAction($post_id)
    {
        $post = $this->findOne('Post', $post_id);

        if (!$post) {
            return $this->notFound(sprintf('Article #%s not found', $post_id), false);
        }

        if (!$this->get('security.context')->isGranted('DELETE', $post) &&
                !$this->get('security.context')->isGranted('ROLE_MODERATE')) {
            return $this->accessDenied(null, false);
                }

        if ($confirm = $this->get('request')->query->get('confirm')) {

            $em = $this->getEm();
            $em->remove($post);
            $em->flush();

            $this->flash('Post deleted');

            return $this->myRedirect('_posts');
        }

        return $this->renderTpl($this->_name . ':post_delete', compact('post'));
    }

}
