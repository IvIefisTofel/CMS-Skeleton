<?php
namespace MCms\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetSiteParam extends AbstractHelper
{
    /**
     * Функция возвращает значение параметра alias из базы данных
     * @param  $alias String
     * @return mixed
     */
    public function __invoke($alias)
    {
        $om = $this->getView()->getHelperPluginManager()->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $setting = $om->getRepository('Settings\Entity\Settings')->findOneByName($alias);
        /* @var $setting \Settings\Entity\Settings */
        $result = null;
        if ($setting) {
            $result = $setting->getValue();
        }

        return $result;
    }
}