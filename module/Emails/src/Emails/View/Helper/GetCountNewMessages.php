<?php
namespace Emails\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetCountNewMessages extends AbstractHelper
{
    /**
     * Функция возвращает количество не прочитанных сообщений
     * @return integer
     */
    public function __invoke()
    {
        $om = $this->getView()->getHelperPluginManager()->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $queryBuilder = $om->getRepository('Emails\Entity\Email')->createQueryBuilder(\Emails\Entity\Email::PRIMARY);
        $queryBuilder->select('COUNT(' . \Emails\Entity\Email::PRIMARY . '.' . \Emails\Entity\Email::PRIMARY . ')');
        $queryBuilder->where(\Emails\Entity\Email::PRIMARY . '.read=?0')->setParameter(0, false);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}