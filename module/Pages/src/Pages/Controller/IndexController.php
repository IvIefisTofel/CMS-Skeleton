<?php
namespace Pages\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;

class IndexController extends MCmsController
{
    public function indexAction()
    {
        $alias = $this->params()->fromRoute('alias', 0);

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /* @var $page \Pages\Entity\Pages */
        $page = $om->getRepository('Pages\Entity\Pages')->findOneByAlias($alias);

        if (!$page || !$page->getActive() || new \DateTime() < $page->getDate()) {
            $view = new ViewModel();
            $view->setTemplate('layout/layout');
            $this->getResponse()->setStatusCode(404);

            return $view;
        }

        $plugin = $this->plugin('LitHelperPlugin');
        /* @var $plugin \MCms\Controller\Plugin\HelperPlugin */
        $seo = $plugin->getSeo($page->getAlias());
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
            'seo' => $seo,
        ]);

        return $view;
    }
}