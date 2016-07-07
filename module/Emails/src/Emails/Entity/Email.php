<?php

namespace Emails\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="lit_emails")
 * @ORM\Entity
 */
class Email
{
    const PRIMARY = 'emailId';

    const SYSTEM_MESSAGE = 'system::message';

    const TYPE_IMPORTANT    = 0;
    const TYPE_ORDER        = 1;
    const TYPE_FEEDBACK     = 2;
    const TYPE_FAQ          = 3;
    const TYPE_ERROR        = 4;

    static $arrTypes = [
        self::TYPE_IMPORTANT => [
            'name'          => 'important',
            'text'          => 'Важно',
            'colorClass'    => 'warning',
            'class'         => 'important',
        ],
        self::TYPE_ORDER => [
            'name'          => 'order',
            'text'          => 'Заказ',
            'colorClass'    => 'primary',
            'class'         => 'order',
        ],
        self::TYPE_FEEDBACK => [
            'name'          => 'feedback',
            'text'          => 'Обратная связь',
            'colorClass'    => 'info',
            'class'         => 'feedback',
        ],
        self::TYPE_FAQ => [
            'name'          => 'faq',
            'text'          => 'Вопрос в FAQ',
            'colorClass'    => 'info',
            'class'         => 'faq',
        ],
        self::TYPE_ERROR => [
            'name'          => 'error',
            'text'          => 'Ошибка',
            'colorClass'    => 'danger',
            'class'         => 'error',
        ],
    ];

    /**
     * @var integer
     *
     * @ORM\Column(name="emailId", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $emailId;

    /**
     * @var integer
     *
     * @ORM\Column(name="typeId", type="integer", precision=0, scale=0, nullable=false, unique=false)
     */
    private $typeId;

    /**
     * @var string
     *
     * @ORM\Column(name="flags", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $flags = 'a:0:{}';

    /**
     * @var string
     *
     * @ORM\Column(name="fromUrl", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $fromUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="senderName", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $senderName;

    /**
     * @var string
     *
     * @ORM\Column(name="senderEmail", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $senderEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", precision=0, scale=0, nullable=false, unique=false)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="readed", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $read = false;


    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Get emailId
     *
     * @return integer
     */
    public function getId()
    {
        return $this->emailId;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Email
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set flags
     *
     * @param array $flags
     * @return Email
     */
    public function setFlags($flags)
    {
        if (is_array($flags))
            $this->flags = serialize($flags);

        return $this;
    }

    /**
     * Get flags
     *
     * @return array
     */
    public function getFlags()
    {
        return unserialize($this->flags);
    }

    /**
     * Set fromUrl
     *
     * @param string $fromUrl
     * @return Email
     */
    public function setFromUrl($fromUrl)
    {
        $this->fromUrl = $fromUrl;

        return $this;
    }

    /**
     * Get fromUrl
     *
     * @return string
     */
    public function getFromUrl()
    {
        return $this->fromUrl;
    }

    /**
     * Set senderName
     *
     * @param string $senderName
     * @return Email
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;

        return $this;
    }

    /**
     * Get senderName
     *
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * Set senderEmail
     *
     * @param string $senderEmail
     * @return Email
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;

        return $this;
    }

    /**
     * Get senderEmail
     *
     * @return string
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Email
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Email
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Email
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

    /**
     * Set read
     *
     * @param boolean $read
     * @return Email
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean
     */
    public function getRead()
    {
        return $this->read;
    }
}