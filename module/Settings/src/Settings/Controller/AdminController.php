<?php
namespace Settings\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class AdminController extends MCmsController
{
    /**
     * @return JsonModel|ViewModel
     */
    public function indexAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $request = $this->getRequest();
        if($request->isXmlHttpRequest() && $request->isPost()) {
            $data = $request->getPost();

            switch ($data['action']) {
                case 'editSettings': {
                    $counter = 0;
                    foreach($data['data'] as $value) {
                        /* @var $setting \Settings\Entity\Settings */
                        $setting = $om->getRepository('Settings\Entity\Settings')->findOneByName($value['name']);
                        $setting->setValue($value['value']);
                        $om->persist($setting);
                        $counter++;
                    }
                    $om->flush();

                    $numEnding = $this->getServiceLocator()->get('ViewHelperManager')->get('numending');

                    $message = $numEnding($counter, ['Обновлена', 'Обновлены', 'Обновлены'])
                        . " $counter " . $numEnding($counter, ['запись', 'записи', 'записей']);

                    return new JsonModel([
                        'error' => false,
                        'msgHeader' => 'Успешно обновлено!',
                        'message' => $message
                    ]);
                    break;
                }
                case 'changeWeight':
                    foreach($data['data'] as $weight => $id) {
                        $id = str_replace('settingID-', '', $id);
                        $weight = $weight + 1;
                        /* @var $setting \Settings\Entity\Settings */
                        $setting = $om->getRepository('Settings\Entity\Settings')->find($id);
                        if ($setting != null) {
                            $setting->setWeight($weight);
                            $om->persist($setting);
                        }
                    }
                    $om->flush();
                    return new JsonModel([
                        'error' => false,
                        'message' => 'Weight successfully changed.',
                    ]);
                    break;
                default: {
                    return new JsonModel([
                        'error' => true,
                        'message' => 'Unknown action parameter.',
                    ]);
                    break;
                }
            }
        } elseif ($request->isPost()) {
            foreach($request->getPost() as $name => $value) {
                /* @var $setting \Settings\Entity\Settings */
                $setting = $om->getRepository('Settings\Entity\Settings')->findOneByName($name);
                $setting->setValue($value);
                $om->persist($setting);
            }
            $om->flush();
        }

        $groups = $om->getRepository('Settings\Entity\Groups')->findBy([], ['weight' => 'ASC']);
        foreach ($groups as $id => $group) {
            $settings = $om->getRepository('Settings\Entity\Settings')->findByGroupID($group->getGroupID(), ['weight' => 'ASC']);
            if (count($settings) > 0) {
                $groups[$id] = [
                    'group' => $group,
                    'settings' => $settings,
                ];
            } else {
                unset($groups[$id]);
            }
        }

        $viewModel = new ViewModel([
            'groups' => $groups,
        ]);

        return $viewModel;
    }

    /**
     * @return JsonModel|ViewModel
     */
    public function groupsAction()
    {
        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $groups = $om->getRepository('Settings\Entity\Groups')->findBy([], ['weight' => 'ASC']);

        return new ViewModel([
            'groups' => $groups,
        ]);
    }


    /**
     * @return JsonModel
     */
    public function groupssortAction()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest() && $request->isPost()) {
            $data = $request->getPost();
            switch ($data['action']) {
                case 'changeWeight': {
                    $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                    foreach($data['data'] as $id => $weight) {
                        /* @var $group \Settings\Entity\Groups */
                        $group = $om->getRepository('Settings\Entity\Groups')->find($id);
                        if ($group != null) {
                            $group->setWeight($weight);
                            $om->persist($group);
                        }
                    }
                    $om->flush();
                    return new JsonModel([
                        'error' => false,
                        'message' => 'Порядок успешно изменен.',
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
        }
        return new JsonModel([
            'error' => true,
            'message' => 'Unknown action parameter.',
        ]);
    }

    /**
     * @return JsonModel|ViewModel
     */
    public function editgroupAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $bError = false;
        $arrErrorsTexts = [];

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /* @var $group \Settings\Entity\Groups */
        $group = $om->getRepository('Settings\Entity\Groups')->find($id);

        if ($group != null) {
            $request = $this->getRequest();
            if($request->isXmlHttpRequest() && $request->isPost()) {
                $data = $request->getPost();
                switch ($data['action']) {
                    case 'editGroup': {
                        $name = trim($data['data'][0]['value']);
                        if ($name != '') {
                            $group->setName($name);
                            $om->persist($group);
                            $om->flush();

                            return new JsonModel([
                                'error' => false,
                                'message' => 'Запись успешно изменена.',
                            ]);
                        } else {
                            return new JsonModel([
                                'error' => true,
                                'msgHeader' => 'Ошибка',
                                'message' => 'Пустое имя группы.',
                            ]);
                        }
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
            } elseif ($request->isPost()) {
                $data = $request->getPost();
                $name = trim($data['name']);
                if ($name != '') {
                    $group->setName($name);
                    $om->persist($group);
                    $om->flush();

                    return $this->redirect()->toRoute('admin-settings', ['action' => 'groups']);
                } else {
                    $bError = true;
                    $arrErrorsTexts['name'] = 'Пустое имя группы.';
                }
            }
        } else {
            $bError = true;
            $arrErrorsTexts['main'][] = 'Группа настроек с индексом "' . $id . '" не найдена.';
        }

        $view = new ViewModel([
            'error' => $bError,
            'arrErrorsTexts' => $arrErrorsTexts,
            'group' => $group,
        ]);
        $view->setTemplate('settings/admin/editgroup');

        return $view;
    }

    /**
     * @return JsonModel|ViewModel
     */
    public function addgroupAction()
    {
        $bError = false;
        $arrErrorsTexts = [];

        $om = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $group = new \Settings\Entity\Groups();

        $request = $this->getRequest();
        if($request->isXmlHttpRequest() && $request->isPost()) {
            $data = $request->getPost();
            switch ($data['action']) {
                case 'addGroup': {
                    $name = trim($data['data'][0]['value']);
                    if ($name != '') {
                        $count = $om->getRepository('Settings\Entity\Groups')->createQueryBuilder('a')->select('COUNT(a)')->getQuery()->getSingleScalarResult();

                        $group->setName($name);
                        $group->setWeight($count + 1);

                        $om->persist($group);
                        $om->flush();

                        return new JsonModel([
                            'error' => false,
                            'message' => 'Группа успешно создана.',
                            'id' => $group->getGroupID(),
                            'name' => $group->getName(),
                        ]);
                    } else {
                        return new JsonModel([
                            'error' => true,
                            'msgHeader' => 'Ошибка',
                            'message' => 'Пустое имя группы.',
                        ]);
                    }
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
        } elseif ($request->isPost()) {
            $data = $request->getPost();
            $name = trim($data['name']);
            if ($name != '') {

                $count = $om->getRepository('Settings\Entity\Groups')->createQueryBuilder('a')->select('COUNT(a)')->getQuery()->getSingleScalarResult();

                $group->setName($name);
                $group->setWeight($count + 1);

                $om->persist($group);
                $om->flush();
                return $this->redirect()->toRoute('admin-settings', ['action' => 'groups']);
            } else {
                $bError = true;
                $arrErrorsTexts['name'] = 'Пустое имя группы.';
            }
        }

        $view = new ViewModel([
            'error' => $bError,
            'arrErrorsTexts' => $arrErrorsTexts,
            'group' => $group,
        ]);
        $view->setTemplate('settings/admin/editgroup');

        return $view;
    }
}