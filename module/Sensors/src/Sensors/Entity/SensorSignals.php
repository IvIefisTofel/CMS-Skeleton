<?php

namespace Sensors\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SensorSignals
 *
 * @ORM\Table(name="lit_sensor_signals")
 * @ORM\Entity
 */
class SensorSignals
{
    /**
     * @var integer
     *
     * @ORM\Column(name="signalID", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $signalid;

    /**
     * @var integer
     *
     * @ORM\Column(name="sensorID", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $sensorid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", precision=0, scale=0, nullable=true, unique=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="mainPageDescription", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $mainpagedescription;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $alias;

    /**
     * @var integer
     *
     * @ORM\Column(name="weight", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $weight;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=1, precision=0, scale=0, nullable=false, unique=false)
     */
    private $active;


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
     * @return SensorSignals
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
     * Set name
     *
     * @param string $name
     * @return SensorSignals
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
     * Set description
     *
     * @param string $description
     * @return SensorSignals
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set mainpagedescription
     *
     * @param string $mainpagedescription
     * @return SensorSignals
     */
    public function setMainpagedescription($mainpagedescription)
    {
        $this->mainpagedescription = $mainpagedescription;

        return $this;
    }

    /**
     * Get mainpagedescription
     *
     * @return string 
     */
    public function getMainpagedescription()
    {
        return $this->mainpagedescription;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return SensorSignals
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set weight
     *
     * @param integer $weight
     * @return SensorSignals
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

    /**
     * Set active
     *
     * @param string $active
     * @return SensorSignals
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return string 
     */
    public function getActive()
    {
        return $this->active;
    }
}
