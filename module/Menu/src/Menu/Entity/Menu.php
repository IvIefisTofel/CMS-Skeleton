<?php

namespace Menu\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="lit_menu")
 * @ORM\Entity
 */
class Menu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="menuId", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $menuId;

    /**
     * @var string
     *
     * @ORM\Column(name="menuLabel", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $menuLabel;

    /**
     * @var string
     *
     * @ORM\Column(name="menuURL", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $menuUrl;

    /**
     * @var boolean
     *
     * @ORM\Column(name="menuActive", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $menuActive;

    /**
     * @var integer
     *
     * @ORM\Column(name="menuWeight", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $menuWeight;


    /**
     * Get menuId
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->menuId;
    }

    /**
     * Set menuLabel
     *
     * @param string $menuLabel
     * @return Menu
     */
    public function setLabel($menuLabel)
    {
        $this->menuLabel = $menuLabel;

        return $this;
    }

    /**
     * Get menuLabel
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->menuLabel;
    }

    /**
     * Set menuUrl
     *
     * @param string $menuUrl
     * @return Menu
     */
    public function setUrl($menuUrl)
    {
        $this->menuUrl = $menuUrl;

        return $this;
    }

    /**
     * Get menuUrl
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->menuUrl;
    }

    /**
     * Set menuActive
     *
     * @param boolean $menuActive
     * @return Menu
     */
    public function setActive($menuActive)
    {
        $this->menuActive = $menuActive;

        return $this;
    }

    /**
     * Get menuActive
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->menuActive;
    }

    /**
     * Set menuWeight
     *
     * @param integer $menuWeight
     * @return Menu
     */
    public function setWeight($menuWeight)
    {
        $this->menuWeight = $menuWeight;

        return $this;
    }

    /**
     * Get menuWeight
     *
     * @return integer 
     */
    public function getWeight()
    {
        return $this->menuWeight;
    }
}
