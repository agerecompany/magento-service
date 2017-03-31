<?php
/**
 * @category Agere
 * @package Agere_Service
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 21.01.14 18:30
 */
class Agere_Service_Helper_Data extends Mage_Core_Helper_Abstract {

	const XML_PATH_CALLBACK_RECIPIENT = '/general/recipient_email';
	const XML_PATH_CALLBACK_SENDER = '/general/sender_email_identity';
	const XML_PATH_CALLBACK_TEMPLATE = '/general/email_template';
	const XML_PATH_ENABLED = '/general/enabled';

	const GOOGLE_CAPTCHA_SECRET = '6LexLhAUAAAAAPEnTRd5ajKlf0IH1GC2-2kN2aDX';
	const GOOGLE_CAPTCHA_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Ці значення було перенесено помодульно
     */
	const SECTION_NAME_WRITE_STO = 'agere_service_write_sto';
	const SECTION_NAME_TEST_DRIVE = 'agere_service_test_drive';
	const SECTION_NAME_RATE_CAR = 'agere_service_rate_car';
	const SECTION_NAME_CALL_ME = 'agere_service_call_me';
	const SECTION_NAME_ASK_QUESTION = 'agere_service_ask_question';
	const SECTION_NAME_OFFER_COMMERCE = 'agere_service_offer_commerce';

	protected $formType = array('advanced', 'simple');

	/**
	 * @param string $sectionName
	 * @return string
	 */
	public function getFormType($sectionName) {
		$enabled = Mage::getStoreConfig($sectionName . self::XML_PATH_ENABLED);

		return $this->formType[$enabled];
	}

	public function handleForm($sectionName, $data) {
		$helperName = '';
		list($packageName, $moduleName, $helperParts) =  explode('_', $sectionName, 3);
		foreach (explode('_', $helperParts) as $key => $part) {
			if ($key > 0) {
				$part = ucfirst($part);
			}
			$helperName .= $part;
		}

		$name = $packageName . '_' . $moduleName . '/' . $helperName;
		$helper = Mage::helper($name);
		$formType = $this->getFormType($sectionName);
		$method = 'handle' . ucfirst($formType) . 'Form';
		return $helper->{$method}($data);
	}


	/**
	 * Check captcha
	 */
	public function checkCaptcha($captcha, $name = '') {
		session_start();
		// Порівняння без врахування регістру
		if (isset($_SESSION['captcha_keystring']) && ($_SESSION['captcha_keystring'] != '') && (strcasecmp($_SESSION['captcha_keystring'], $captcha) == 0)) {
			unset($_SESSION['captcha_keystring']); // Обязательное удаление
			return true;
		} elseif ($name && isset($_SESSION['captcha'][$name]) && ($_SESSION['captcha'][$name] != '') && (strcasecmp($_SESSION['captcha'][$name], $captcha) == 0)) {
			unset($_SESSION['captcha'][$name]); // Обов'язкове видалення
			return true;
		} else {
			Mage::getSingleton('core/session')->addError('Неверный защитный код, попробуйте еще раз.');
			//Mage::getSingleton('core/session')->setPopScript('jQuery("#osolCatchaTxt3").addClass("validation-failed");jQuery("#osolCatchaTxt3").after("<div id=\"advice-required-entry-osolCatchaTxt3\" class=\"validation-advice\">Неверный защитный код.</div>");');
			return false;
		}
	}

	/**
	 * Check captcha
	 */
	public function checkGoogleCaptcha($post) {
		if(isset($post['g-recaptcha-response']) && !empty($post['g-recaptcha-response'])) {
			//get verify response data
			$verifyResponse = file_get_contents(self::GOOGLE_CAPTCHA_URL .'?secret='.self::GOOGLE_CAPTCHA_SECRET .'&response='.$post['g-recaptcha-response']);
			$responseData = json_decode($verifyResponse);
			if(!$responseData->success) {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

		/**
	 * @param $valSelected
	 * @param array $options
	 * @return string
	 */
	protected function _getOptionsHtml($valSelected, array $options) {
		$strOptions = '<option value="">' . $this->__('-- Please Select --') . '</option>';
		foreach ($options as $key => $option) {
			$selected = ($key == $valSelected) ? ' selected=""' : '';
			$strOptions .= '<option value="' . $key . '"' . $selected . '>' . $option . '</option>';
		}

		return $strOptions;
	}
}