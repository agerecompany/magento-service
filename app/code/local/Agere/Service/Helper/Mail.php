<?php
/**
 * @category Agere
 * @package Agere_Service
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 21.01.14 18:30
 */
class Agere_Service_Helper_Mail extends Mage_Core_Helper_Abstract
{
	const XML_PATH_CALLBACK_RECIPIENT = '/mail/recipient_email';
	const XML_PATH_CALLBACK_SENDER = '/mail/sender_email_identity';
	const XML_PATH_CALLBACK_TEMPLATE = '/mail/email_template';

    public function sendSimpleMail(array $data, $sectionName)
    {
        $storeId = Mage::app()->getStore()->getId();
        $mailTemplate = Mage::getModel('core/email_template');
        /** @var Mage_Core_Model_Email_Template $mailTemplate */
        $mailTemplate->setDesignConfig(array('area' => 'frontend'));
        $email = Mage::getStoreConfig($sectionName . self::XML_PATH_CALLBACK_RECIPIENT);
        $mailTemplate->sendTransactional(
            Mage::getStoreConfig($sectionName . self::XML_PATH_CALLBACK_TEMPLATE),
            Mage::getStoreConfig($sectionName . self::XML_PATH_CALLBACK_SENDER),
            explode(';', $email),
            null,
            //$data,
            array(
                'data' => new Varien_Object($data),
                'website' => Mage::app()->getWebsite($storeId),
            ),
            $storeId
        );
        if ($mailTemplate->getSentSuccess()) {
            Mage::getSingleton('core/session')->addSuccess('Письмо успешно отправлено');
            return true;
        }
    }
}