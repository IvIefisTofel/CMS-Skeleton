<?php

namespace Sensors\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SensorCalls
 *
 * @ORM\Table(name="lit_sensor_calls")
 * @ORM\Entity
 */
class SensorCalls
{
    /**
     * @var integer
     *
     * @ORM\Column(name="callID", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $callid;

    /**
     * @var integer
     *
     * @ORM\Column(name="signalID", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $signalid;

    /**
     * @var integer
     *
     * @ORM\Column(name="sensorID", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $sensorid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $date;


    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Get callid
     *
     * @return integer 
     */
    public function getCallid()
    {
        return $this->callid;
    }

    /**
     * Set signalid
     *
     * @param integer $signalid
     * @return SensorCalls
     */
    public function setSignalid($signalid)
    {
        $this->signalid = $signalid;

        return $this;
    }

    /**
     * Get signalid
     *
     * @return integer 
     */
    public function getSignalid()
    {
        return $this->signalid;
    }

    /**
     * Set sensorid
     *
     * @param integer $sensorid
     * @return SensorCalls
     */
    public function setSensorid($sensorid)
    {
        $this->sensorid = $sensorid;

        return $this;
    }

    /**
     * Get sensorid
     *
     * @return integer 
     */
    public function getSensorid()
    {
        return $this->sensorid;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return SensorCalls
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
}
