<?php
namespace Emails\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class AdminController extends MCmsController
{
    public function indexAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $queryBuilder = $om->getRepository('Emails\Entity\Email')->createQueryBuilder(\Emails\Entity\Email::PRIMARY);
        $queryBuilder->orderBy(\Emails\Entity\Email::PRIMARY . '.date', 'DESC');

        $adapter = new DoctrinePaginator(new ORMPaginator($queryBuilder));
        $paginator = new Paginator($adapter);
        $paginator->setPageRange(5);
        $page = (int)$this->params()->fromRoute('page', 1);

        $paginator->setCurrentPageNumber($page)->setItemCountPerPage(25);

        return new ViewModel([
            'paginator' => $paginator,
        ]);
    }

    public function messageAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $mail = $om->getRepository('Emails\Entity\Email')->find($id);
        /* @var $mail \Emails\Entity\Email */

        if (!$mail) {
            $view = new ViewModel();
            $view->setTemplate('layout/layout');
            $this->getResponse()->setStatusCode(404);

            return $view;
        } elseif (!$mail->getRead()) {
            $mail->setRead(true);
            $om->persist($mail);
            $om->flush();
        }

        return new ViewModel([
            'mail' => $mail,
        ]);
    }

    public function deleteMessageAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $mail = $om->getRepository('Emails\Entity\Email')->find($id);
        /* @var $mail \Emails\Entity\Email */

        if ($mail) {
            $om->remove($mail);
            $om->flush();
        }

        return $this->redirect()->toRoute('admin-mail');
    }

    public function ajaxAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if($request->isXmlHttpRequest() && $request->isPost()) {
            $data = $request->getPost();
            switch ($data['action']) {
                case 'setAsReaded': {
                    $mails = $om->getRepository('Emails\Entity\Email')->findByEmailId($data['data']);
                    foreach ($mails as $mail) {
                        /* @var $mail \Emails\Entity\Email */
                        $mail->setRead(true);
                        $om->persist($mail);
                    }
                    $om->flush();

                    return new JsonModel(array(
                        'error' => false,
                        'type' => 'setRead',
                        'message' => 'Mails successfully updated. ',
                    ));
                    break;
                }
                case 'delete': {
                    $mails = $om->getRepository('Emails\Entity\Email')->findByEmailId($data['data']);
                    foreach ($mails as $mail) {
                        /* @var $mail \Emails\Entity\Email */
                        $om->remove($mail);
                    }
                    $om->flush();

                    return new JsonModel(array(
                        'error' => false,
                        'type' => 'delete',
                        'message' => 'Mails successfully deleted. ',
                    ));

                    break;
                }
                case 'toggleImportant': {
                    $mail = $om->getRepository('Emails\Entity\Email')->find($data['data']);
                    /* @var $mail \Emails\Entity\Email */
                    $flags = $mail->getFlags();
                    if (isset($flags[\Emails\Entity\Email::TYPE_IMPORTANT])) {
                        unset($flags[\Emails\Entity\Email::TYPE_IMPORTANT]);
                        $important = false;
                    } else {
                        $flags = array_merge([\Emails\Entity\Email::TYPE_IMPORTANT => true], $flags);
                        $important = true;
                    }
                    $mail->setFlags($flags);
                    $om->persist($mail);
                    $om->flush();

                    return new JsonModel(array(
                        'error' => false,
                        'important' => $important,
                        'message' => 'Mails successfully updated. ',
                    ));
                    break;
                }
                default: {
                    return new JsonModel(array(
                        'error' => true,
                        'message' => 'Unknown action parameter.',
                    ));
                    break;
                }
            }
        } else {
            $this->getResponse()->setStatusCode(404);
            return new ViewModel();
        }
    }
}