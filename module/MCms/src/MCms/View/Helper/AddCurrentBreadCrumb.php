<?php
namespace MCms\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AddCurrentBreadCrumb extends AbstractHelper
{
    /**
     * @param  $label string
     * @param  $icon Array
     * @return boolean
     */
    public function __invoke($label, $icon)
    {
        if ($label == null)
            return false;

        /* @var $view \Zend\View\Model\ViewModel */
        $view = $this->getView()->viewModel()->getRoot();

        $view->setVariable('currBreadCrumb', [
            'label' => $label,
            'icon' => $icon,
        ]);

        return true;
    }
}