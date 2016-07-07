<?php
namespace Admin\Controller;

use MCms\Controller\MCmsController;
use Zend\View\Model\ViewModel;

class IndexController extends MCmsController
{
    public function indexAction()
    {
        $dateStart = new \DateTime();
        $dateStart->modify('-1 month');
        $dateEnd = new \DateTime();

        $metrikaSettings = $this->entityManager->getRepository('Settings\Entity\Settings')->findByName(['yandex_metrika_id', 'yandex_metrika_token', 'yandex_metrika_demo']);

        /** @var \Settings\Entity\Settings $value */
        foreach ($metrikaSettings as $value) {
            $metrikaSettings[$value->getName()] = $value->getValue();
        }
        if ($metrikaSettings['yandex_metrika_demo']) {
            $json_data = \Settings\Entity\Settings::getDemoMetrika();
        } else {
            $json_data = null;
            if (isset($metrikaSettings['yandex_metrika_id']) && isset($metrikaSettings['yandex_metrika_token'])) {
                $url = 'https://api-metrika.yandex.ru/stat/traffic/summary.json?id='.$metrikaSettings['yandex_metrika_id'] .
                    '&oauth_token='.$metrikaSettings['yandex_metrika_token'] .
                    '&date1='.date_format($dateStart, 'Ymd') . '&date2='.date_format($dateEnd, 'Ymd');
                $json_data = file_get_contents($url);
                $json_data = json_decode($json_data, true);
            }
        }

        return new ViewModel([
            'json' => $json_data,
        ]);
    }
}