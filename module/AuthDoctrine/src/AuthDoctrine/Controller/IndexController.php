<?php
namespace AuthDoctrine\Controller;

use MCms\Controller\MCmsController;

class IndexController extends MCmsController
{
    public function indexAction()
    {
		return $this->redirect()->toRoute('logout');
    }
	
	public function logoutAction()
	{
		$auth = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
		$auth->clearIdentity();

		$sessionManager = new \Zend\Session\SessionManager();
		$sessionManager->forgetMe();
        setcookie('lockScreen', '', -3600, '/', $this->getRequest()->getUri()->getHost());
		
		return $this->redirect()->toRoute('home');
	}
}