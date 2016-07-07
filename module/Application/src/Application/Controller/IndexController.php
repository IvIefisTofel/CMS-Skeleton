<?php

namespace Application\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;

class IndexController extends MCmsController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}