<?php
namespace AuthDoctrine\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;
use AuthDoctrine\Form\LoginForm;

class AdminController extends MCmsController
{
    public function indexAction()
    {
        return $this->redirect()->toRoute('admin-login');
    }

    public function loginAction()
    {
        /* @var $user \Users\Entity\User */
        if ($user = $this->identity()) {
            if (isset($_COOKIE['lockScreen'])) {
                return $this->redirect()->toRoute('lock-screen');
            } else {
                return $this->redirect()->toRoute('admin');
            }
        }

        $authErrors = [];

        $loginForm = new LoginForm();
        $loginForm->setAttribute('action', $this->url()->fromRoute('admin-login'));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $loginForm->setData($data);
            if ($loginForm->isValid()) {
                $loginForm->getData();

                $authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
                $adapter = $authService->getAdapter();
                $adapter->setIdentityValue($data[LoginForm::NAME]);
                $adapter->setCredentialValue($data[LoginForm::PASSWORD]);
                $authResult = $authService->authenticate();

                if ($authResult->isValid()) {
                    $identity = $authResult->getIdentity();
                    $authService->getStorage()->write($identity);
                    if ($data[LoginForm::REMEMBER]) {
                        $sessionManager = new \Zend\Session\SessionManager();
                        $time = 60 * 60 * 24 * 7; // 60(seconds)*60(minutes)*24(hours) = 86400 = 1 day
                        $sessionManager->rememberMe($time);
                    }
                    return $this->redirect()->toRoute('admin');
                } else {
                    $authErrors['auth'] = 'Не верная комбинация логина и пароля.';
                }
            }
        }

        $view = new ViewModel(array(
            'authErrors' => $authErrors,
            'loginForm'	=> $loginForm,
        ));
        $view->setTerminal(true);

        return $view;
	}

    public function lockScreenAction()
    {
        /* @var $user \Users\Entity\User */
        if ($user = $this->identity()) {
            setcookie('lockScreen', 'true', time() + 60*60*24, '/', $this->getRequest()->getUri()->getHost());
        } else {
            return $this->redirect()->toRoute('admin-login');
        }

        $uri = $this->params()->fromQuery('uri');
        if ($uri == 'tosite') {
            return $this->redirect()->toRoute('home');
        }

        $error = false;

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            if ($user->getUserPassword() == md5($data['lockScreenPass'])) {
                setcookie('lockScreen', '', -3600, '/', $this->getRequest()->getUri()->getHost());
                if ($data['lockScreenRedirect'] != null) {
                    return $this->redirect()->toUrl($data['lockScreenRedirect']);
                } else {
                    return $this->redirect()->toRoute('admin');
                }
            } else {
                $error = true;
            }
        }

        $view = new ViewModel(array(
            'error' => $error,
            'uri' => $uri,
        ));
        $view->setTerminal(true);

        return $view;
    }
}