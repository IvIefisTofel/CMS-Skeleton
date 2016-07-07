<?php
namespace News\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;

class AdminController extends MCmsController
{
    public function indexAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $news = $om->getRepository('News\Entity\News')->findAll();

        return new ViewModel([
            'arrNews' => $news,
        ]);
    }

    public function newsPreviewAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /* @var $news \News\Entity\News */
        $news = $om->getRepository('News\Entity\News')->find($id);

        if (!$news) { //|| !$news->getActive() || new \DateTime() < $news->getDate()) {
            $view = new ViewModel();
            $view->setTemplate('layout/layout');
            $this->getResponse()->setStatusCode(404);

            return $view;
        }

        $view = new ViewModel([
            'news' => $news,
        ]);
        $this->layout('layout/layout');

        return $view;
    }

    public function addNewsAction()
    {
        $arrErrors = [];

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $news = new \News\Entity\News();

        $plugin = $this->plugin('LitHelperPlugin');
        /* @var $plugin \MCms\Controller\Plugin\HelperPlugin */

        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $request->getPost();

            $findNews = $om->getRepository('News\Entity\News')->findOneByAlias($data['alias']);

            if (!$findNews) {
                // write SEO info
                $plugin->setSeo($data['alias'], $data['alias'], [$data['seo_title'], $data['seo_keywords'], $data['seo_description']]);

                if ($data['alias'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Адрес страницы</b>';
                else
                    $news->setAlias($data['alias']);

                if ($data['name'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Название страницы</b>';
                else
                    $news->setName($data['name']);

                if ($data['content'] == null) {
                    $arrErrors[] = 'Не заполнено поле <b>Контент страницы</b>';
                } else {
                    $news->setContent($data['content']);

                    if ($data['content_preview'] == null)
                        $news->setContentPreview($data['content']);
                    else
                        $news->setContentPreview($data['content_preview']);
                }

                $news->setActive($data['active']);
                if ($data['date'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Дата</b>';
                else
                    $news->setDate(new \DateTime($data['date']));

                $om->persist($news);
                $om->flush();

                return $this->redirect()->toRoute('admin-news');
            } else {
                $arrErrors[] = 'Такой <b>Адрес страницы</b> уже существует!';
            }
        }

        $seo = $plugin->getSeo($news->getAlias());

        $view = new ViewModel([
            'addnews' => true,
            'news' => $news,
            'arrErrors' => $arrErrors,
            'seo' => $seo,
        ]);
        $view->setTemplate('news/admin/editnews');

        return $view;
    }

    public function editNewsAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $arrErrors = [];

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $news = $om->getRepository('News\Entity\News')->findOneById($id);
        /* @var $news \News\Entity\News */

        if (!$news) {
            return $this->redirect()->toRoute('admin-news');
        }

        $plugin = $this->plugin('LitHelperPlugin');
        /* @var $plugin \MCms\Controller\Plugin\HelperPlugin */

        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $request->getPost();

            $findPage = null;
            if ($news->getAlias() != $data['alias'])
                $findPage = $om->getRepository('Pages\Entity\Pages')->findOneByAlias($data['alias']);

            if (!$findPage) {
                // write SEO info
                $plugin->setSeo($news->getAlias(), $data['alias'], [$data['seo_title'], $data['seo_keywords'], $data['seo_description']]);

                if ($data['alias'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Адрес страницы</b>';
                else
                    $news->setAlias($data['alias']);

                if ($data['name'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Название страницы</b>';
                else
                    $news->setName($data['name']);

                if ($data['content'] == null) {
                    $arrErrors[] = 'Не заполнено поле <b>Контент страницы</b>';
                } else {
                    $news->setContent($data['content']);

                    if ($data['content_preview'] == null)
                        $news->setContentPreview($data['content']);
                    else
                        $news->setContentPreview($data['content_preview']);
                }

                $news->setActive($data['active']);
                if ($data['date'] == null)
                    $arrErrors[] = 'Не заполнено поле <b>Дата</b>';
                else
                    $news->setDate(new \DateTime($data['date']));

                $om->persist($news);
                $om->flush();

                return $this->redirect()->toRoute('admin-news');
            } else {
                $arrErrors[] = 'Такой <b>alias</b> уже существует!';
            }
        }

        $seo = $plugin->getSeo($news->getAlias());

        return new ViewModel([
            'news' => $news,
            'arrErrors' => $arrErrors,
            'seo' => $seo,
        ]);
    }

    public function deleteNewsAction()
    {
        $id = $this->params()->fromRoute('id', 0);

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $news = $om->getRepository('News\Entity\News')->findOneById($id);
        /* @var $news \News\Entity\News */

        if (!$news) {
            return $this->redirect()->toRoute('admin-news');
        }

        $plugin = $this->plugin('LitHelperPlugin');
        $plugin->setSeo($news->getAlias());

        $om->remove($news);
        $om->flush();

        return $this->redirect()->toRoute('admin-news');
    }
}