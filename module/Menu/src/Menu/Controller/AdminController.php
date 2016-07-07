<?php
namespace Menu\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class AdminController extends MCmsController
{
    public function indexAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $menu = $om->getRepository('Menu\Entity\Menu')->findBy([], ['menuWeight' => 'ASC']);

        return new ViewModel([
            'menu' => $menu,
        ]);
    }

    public function ajaxAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if($request->isXmlHttpRequest() && $request->isPost()) {
            $data = $request->getPost();
            switch ($data['action']) {
                case 'setActive': {
                    $menuItem = $om->getRepository('Menu\Entity\Menu')->findOneByMenuId($data['data']['id']);
                    /* @var $menuItem \Menu\Entity\Menu */
                    $menuItem->setActive((bool)$data['data']['active']);
                    $om->persist($menuItem);
                    $om->flush();

                    return new JsonModel([
                        'error' => false,
                        'message' => 'Успешно обновлено!',
                    ]);
                    break;
                }
                case 'updateParam': {
                    $error = false;
                    $msg = 'Успешно обновлено!';

                    $menuItem = $om->getRepository('Menu\Entity\Menu')->findOneByMenuId($data['data']['id']);
                    /* @var $menuItem \Menu\Entity\Menu */

                    if ($data['data']['name'] == 'label') {
                        $menuItem->setLabel($data['data']['value']);
                        $om->persist($menuItem);
                        $om->flush();
                    } elseif ($data['data']['name'] == 'url') {
                        $menuItem->setUrl($data['data']['value']);
                        $om->persist($menuItem);
                        $om->flush();
                    } else {
                        $error = true;
                        $msg = 'Не верные входные параметры.';
                    }

                    return new JsonModel([
                        'error' => $error,
                        'message' => $msg,
                    ]);
                    break;
                }
                case 'addMenuItem': {
                    $menuItem = new \Menu\Entity\Menu();

                    $menuItem->setLabel($data['data']['label']);
                    $menuItem->setUrl($data['data']['url']);
                    $menuItem->setActive($data['data']['active']);
                    $count = $om->getRepository('Menu\Entity\Menu')->createQueryBuilder('a')->select('COUNT(a)')->getQuery()->getSingleScalarResult();
                    $menuItem->setWeight($count + 1);
                    $om->persist($menuItem);
                    $om->flush();

                    return new JsonModel([
                        'error' => false,
                        'id' => $menuItem->getId(),
                        'message' => 'Запись успешно добавлена!',
                    ]);
                    break;
                }
                case 'changeWeight': {
                    $menu = $om->getRepository('Menu\Entity\Menu')->findByMenuId(array_keys($data['data']));
                    foreach ($menu as $menuItem) {
                        /* @var $menuItem \Menu\Entity\Menu */
                        $menuItem->setWeight($data['data'][$menuItem->getId()]);
                        $om->persist($menuItem);
                    }
                    $om->flush();
                    return new JsonModel([
                        'error' => false,
                        'message' => 'Порядок успешно изменен.',
                    ]);
                    break;
                }
                case 'deleteMenuItem': {
                    $menuItem = $om->getRepository('Menu\Entity\Menu')->findOneByMenuId($data['data']['id']);
                    /* @var $menuItem \Menu\Entity\Menu */
                    $om->remove($menuItem);
                    $om->flush();

                    return new JsonModel([
                        'error' => false,
                        'message' => 'Запись eспешно удалена!',
                    ]);
                    break;
                }
                default: {
                    return new JsonModel([
                        'error' => true,
                        'message' => 'Unknown action parameter.',
                    ]);
                    break;
                }
            }
        } else {
            $this->getResponse()->setStatusCode(404);
            return new ViewModel();
        }
    }
}