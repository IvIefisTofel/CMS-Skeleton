<?php
namespace MCms\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Emails\Entity\Email;

class SendMessage extends AbstractHelper
{
    /**
     * Отправляет письмо в админку
     * @param $typId integer
     * @param $flags array
     * @param $senderName string
     * @param $senderEmail string
     * @param $subject string
     * @param $message string
     * @return boolean
     */
    public function __invoke($typId = null, $flags = null, $subject = null, $message = null, $senderName = null, $senderEmail = null)
    {
        $result = true;

        $mail = new Email();
        $mail->setTypeId(($typId) ? $typId : Email::TYPE_IMPORTANT);
        if ($flags) {
            $mail->setFlags($flags);
        }
        $mail->setFromUrl($this->getView()->serverUrl(true));
        $mail->setSenderName(($senderName) ? $senderName : Email::SYSTEM_MESSAGE);
        $mail->setSenderEmail(($senderEmail) ? $senderEmail : Email::SYSTEM_MESSAGE);
        if ($subject) {
            $mail->setSubject($subject);
        } else {
            $result = false;
        }
        if ($message) {
            $mail->setMessage($message);
        } else {
            $result = false;
        }

        if ($result) {
            $om = $this->getView()->getHelperPluginManager()->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $om->persist($mail);
            $om->flush();
        }

        return $result;
    }
}