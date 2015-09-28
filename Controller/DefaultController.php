<?php

namespace Mipa\SessionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Mipa\SessionBundle\Entity\Users;
use Mipa\SessionBundle\Modals\Login;

class DefaultController extends Controller
{
    public function indexAction(Request $request) {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('MipaSessionBundle:users');
        if ($request->getMethod() == 'POST') {
            $session->clear();
            $username = $request->get('username');
            $password = $request->get('password');
            $remember = $request->get('remember');
            $user = $repository->findOneBy(array('userName' => $username, 'password' => $password));
            if ($user) {
                if ($remember == 'remember-me') {
                    $login = new Login();
                    $login->setUsername($username);
                    $login->setPassword($password);
                    $session->set('login', $login);
                }
                return $this->render('MipaSessionBundle:Default:welcome.html.twig', array('name' => $user->getFirstName()));
            } else {
                return $this->render('MipaSessionBundle:Default:login.html.twig', array('name' => 'Login Error'));
            }
        } else {
            if ($session->has('login')) {
                $login = $session->get('login');
                $username = $login->getUsername();
                $password = $login->getPassword();
                $user = $repository->findOneBy(array('userName' => $username, 'password' => $password));
                if ($user) {
					return $this->render('MipaSessionBundle:Default:welcome.html.twig', array('name' => $user->getFirstName()));

			} }	
					
        return $this->render('MipaSessionBundle:Default:login.html.twig');
    }
	}
			


    public function logoutAction(Request $request) {
        $session = $this->getRequest()->getSession();
        $session->clear();
        return $this->render('MipaSessionBundle:Default:login.html.twig');
    }
}
