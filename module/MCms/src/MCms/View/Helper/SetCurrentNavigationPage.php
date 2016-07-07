<?php
namespace MCms\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SetCurrentNavigationPage extends AbstractHelper
{
    /**
     * @param  $factory string NavigationAbstractServiceFactory
     * @param  $route string
     * @param  $params array
     * @return boolean
     */
    public function __invoke($factory, $route, $params = [])
    {
        $navigation = $this->getView()->getHelperPluginManager()->getServiceLocator()->get('ViewHelperManager')->get('Navigation');
        $navigation = $navigation($factory);
        /* @var $navigation \Zend\Navigation\Navigation */
        $arrFindResult = $navigation->findAllBy('route', $route);

        foreach ($arrFindResult as $page) {
            /* @var $page \Zend\Navigation\Page\Mvc */
            $bCurrentPage = true;
            foreach ($params as $name => $value) {
                if ($page->get($name) != $value) {
                    $bCurrentPage = false;
                    break;
                }
            }
            if ($bCurrentPage) {
                $page->setActive(true);
                return true;
                break;
            }
        }

        return false;
    }
}