<?php
namespace Pages\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;

class AdminController extends MCmsController
{
    public function indexAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $pages = $om->getRepository('Pages\Entity\Pages')->findAll();

        return new ViewModel([
            'pages' => $pages,
        ]);
    }

    public function pagePreviewAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /* @var $page \Pages\Entity\Pages */
        $page = $om->getRepository('Pages\Entity\Pages')->find($id);

        if (!$page) { //|| !$page->getActive() || new \DateTime() < $page->getDate()) {
            $view = new ViewModel();
            $view->setTemplate('layout/layout');
            $this->getResponse()->setStatusCode(404);

            return $view;
        }

        $form = null;
        if ($page->getFormName()) {
            $form = $om->getRepository('Fields\Entity\FieldsValues')->findOneBy([
                'fieldID' => $om->getRepository('Fields\Entity\Fields')->findOneBy(['alias' => 'form'])->getFieldID(),
                'alias' => $page->getFormName(),
            ]);
            if ($form) {
                $form = $form->getValue();
            }
        }

        $view = new ViewModel([
            'page' => $page,
            'form' => $form,
        ]);
        $this->layout('layout/layout');

        return $view;
    }

    public function addPageAction()
    {
        $arrErrors = [];

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $page = new \Pages\Entity\Pages();

        $plugin = $this->plugin('LitHelperPlugin');
        /* @var $plugin \MCms\Controller\Plugin\HelperPlugin */

        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $request->getPost();

            $findPage = $om->getRepository('Pages\Entity\Pages')->findOneByAlias($data['alias']);

            if (!$findPage) {
                // write SEO info
                $plugin->setSeo($data['alias'], $data['alias'], [$data['seo_title'], $data['seo_keywords'], $data['seo_description']]);

                if ($data['alias'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Адрес страницы</b>';
                else
                    $page->setAlias($data['alias']);

                if ($data['name'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Название страницы</b>';
                else
                    $page->setName($data['name']);

                if ($data['content'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Контент страницы</b>';
                else
                    $page->setContent($data['content']);

                $page->setFormName($data['form']);
                $page->setActive($data['active']);
                if ($data['date'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Дата</b>';
                else
                    $page->setDate(new \DateTime($data['date']));

                $om->persist($page);
                $om->flush();

                return $this->redirect()->toRoute('admin-pages');
            } else {
                $arrErrors[] = 'Такой <b>Адрес страницы</b> уже существует!';
            }
        }

        $formsList = $plugin->getFormsList();
        $seo       = $plugin->getSeo($page->getAlias());

        $view = new ViewModel([
            'addpage' => true,
            'page' => $page,
            'arrErrors' => $arrErrors,
            'forms' => $formsList,
            'seo' => $seo,
        ]);
        $view->setTemplate('pages/admin/editpage');

        return $view;
    }

    public function editPageAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $arrErrors = [];

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $page = $om->getRepository('Pages\Entity\Pages')->findOneByPageId($id);
        /* @var $page \Pages\Entity\Pages */

        if (!$page) {
            return $this->redirect()->toRoute('admin-pages');
        }

        $plugin = $this->plugin('LitHelperPlugin');
        /* @var $plugin \MCms\Controller\Plugin\HelperPlugin */

        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $request->getPost();

            $findPage = null;
            if ($page->getAlias() != $data['alias'])
                $findPage = $om->getRepository('Pages\Entity\Pages')->findOneByAlias($data['alias']);

            if (!$findPage) {
                // write SEO info
                $plugin->setSeo($page->getAlias(), $data['alias'], [$data['seo_title'], $data['seo_keywords'], $data['seo_description']]);

                if ($data['alias'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Адрес страницы</b>';
                else
                    $page->setAlias($data['alias']);

                if ($data['name'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Название страницы</b>';
                else
                    $page->setName($data['name']);

                if ($data['content'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Контент страницы</b>';
                else
                    $page->setContent($data['content']);

                $page->setFormName($data['form']);
                $page->setActive($data['active']);
                if ($data['date'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Дата</b>';
                else
                    $page->setDate(new \DateTime($data['date']));

                $om->persist($page);
                $om->flush();

                return $this->redirect()->toRoute('admin-pages');
            } else {
                $arrErrors[] = 'Такой <b>alias</b> уже существует!';
            }
        }

        $formsList = $plugin->getFormsList();
        $seo       = $plugin->getSeo($page->getAlias());

        return new ViewModel([
            'page' => $page,
            'arrErrors' => $arrErrors,
            'forms' => $formsList,
            'seo' => $seo,
        ]);
    }

    public function deletePageAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $page = $om->getRepository('Pages\Entity\Pages')->findOneByPageId($id);
        /* @var $page \Pages\Entity\Pages */

        if (!$page) {
            return $this->redirect()->toRoute('admin-pages');
        }

        $plugin = $this->plugin('LitHelperPlugin');
        $plugin->setSeo($page->getAlias());

        $om->remove($page);
        $om->flush();

        return $this->redirect()->toRoute('admin-pages');
    }
}