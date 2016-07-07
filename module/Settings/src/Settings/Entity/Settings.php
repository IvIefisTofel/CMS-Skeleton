<?php

namespace Settings\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Stdlib\DateTime;

/**
 * Settings
 *
 * @ORM\Table(name="lit_settings")
 * @ORM\Entity
 */
class Settings
{
    const OAUTH_CLIENT_ID       = 'd8de135c2b1045edb2199b258b9f23f0';
    const OUATH_GET_TOKEN_URL   = 'https://oauth.yandex.ru/authorize?response_type=token&client_id=' . self::OAUTH_CLIENT_ID;
    
    static public function getDemoMetrika(){
        $dateEnd = new \DateTime();
        $dateStart = date_sub(new \DateTime(), date_interval_create_from_date_string('1 month'));
        $interval = $dateEnd->diff($dateStart)->days;

        $totalVisits = 0;
        $totalVisitors = 0;
        $totalDepthSum = 0;
        $totalDepthCount = 0;
        $totalPageViews = 0;
        $totalVisitTime = 0;
        $totalNewVisitors = 0;

        $result = [
            'date1' => date_format($dateStart, 'Ymd'),
            'date2' => date_format($dateEnd, 'Ymd'),
            'interval' => $dateEnd->diff($dateStart)->days,
            'id' => 2138128,
            'rows' => $interval,
            'data' => [],
            'totals' => [
                'denial' => 0,
            ],
        ];

        for ($i = 0; $i < $interval; $i++){
            $date = date_add(new \DateTime($dateStart->format('Y-m-d')), date_interval_create_from_date_string((string)($i + 1) . ' days'))->format('Ymd');
            $visits = rand(0, 300);
            $visitors = ($visits > 0) ? rand(1, $visits / 2) : 0;
            $depth = ($visits > 0) ? rand(100, 1000) / 100 : 0;
            $pageViews = ($visits > 0) ? rand($visits, $visits * (rand(10, 30) / 10)) : 0;
            $newVisitors = rand(0, $visitors / 3);
            $visitTime = ($pageViews > 30) ? (rand(30, 10 * $pageViews) * 1.1) : ($pageViews > 0 ? rand(1, 30) : 0);

            $totalVisits += $visits;
            $totalVisitors += $visitors;
            $totalDepthSum += $depth;
            $totalDepthCount ++;
            $totalPageViews += $pageViews;
            $totalNewVisitors += $newVisitors;
            $totalVisitTime += $visitTime;

            $result['data'][] = [
                'denial' => 0,
                'date' => $date,
                'visits' => $visits,
                'visitors' => $visitors,
                'depth' => $depth,
                'page_views' => $pageViews,
                'visit_time' => $visitTime,
                'new_visitors' => $newVisitors,
                'id' => $date,
            ];
        }

        $totalDepth = round($totalDepthSum / $totalDepthCount, 2);

        $result['totals']['visits'] = $totalVisits;
        $result['totals']['visitors'] = $totalVisitors;
        $result['totals']['depth'] = $totalDepth;
        $result['totals']['page_views'] = $totalPageViews;
        $result['totals']['visit_time'] = $totalVisitTime;
        $result['totals']['new_visitors'] = $totalNewVisitors;

        return $result;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="settingID", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $settingID;

    /**
     * @var integer
     *
     * @ORM\Column(name="groupID", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $groupID;

    /**
     * @var string
     *
     * @ORM\Column(name="headerName", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $headerName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="text", precision=0, scale=0, nullable=false, unique=false)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="htmlControlType", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $htmlControlType;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $weight;


    /**
     * Get settingID
     *
     * @return integer
     */
    public function getSettingID()
    {
        return $this->settingID;
    }

    /**
     * Set groupID
     *
     * @param integer $groupID
     * @return Settings
     */
    public function setGroupID($groupID)
    {
        $this->groupID = $groupID;

        return $this;
    }

    /**
     * Get groupID
     *
     * @return integer
     */
    public function getGroupID()
    {
        return $this->groupID;
    }

    /**
     * Set headerName
     *
     * @param string $headerName
     * @return Settings
     */
    public function setHeaderName($headerName)
    {
        $this->headerName = $headerName;

        return $this;
    }

    /**
     * Get headerName
     *
     * @return string
     */
    public function getHeaderName()
    {
        return $this->headerName;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Settings
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return Settings
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set htmlControlType
     *
     * @param string $htmlControlType
     * @return Settings
     */
    public function setHtmlControlType($htmlControlType)
    {
        $this->htmlControlType = $htmlControlType;

        return $this;
    }

    /**
     * Get htmlControlType
     *
     * @return string
     */
    public function getHtmlControlType()
    {
        return $this->htmlControlType;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return Settings
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }
}
