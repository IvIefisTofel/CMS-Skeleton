<?php
namespace News\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;

class IndexController extends MCmsController
{
    public function indexAction()
    {
        $alias = $this->params()->fromRoute('alias', 0);

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /* @var $news \News\Entity\News */
        $news = $om->getRepository('News\Entity\News')->findOneByAlias($alias);

        if (!$news || !$news->getActive() || new \DateTime() < $news->getDate()) {
            $view = new ViewModel();
            $view->setTemplate('layout/layout');
            $this->getResponse()->setStatusCode(404);

            return $view;
        }

        $plugin = $this->plugin('LitHelperPlugin');
        /* @var $plugin \MCms\Controller\Plugin\HelperPlugin */
        $seo = $plugin->getSeo($news->getAlias());

        $view = new ViewModel([
            'news' => $news,
            'seo' => $seo,
        ]);

        return $view;
    }
}