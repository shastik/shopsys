<?php

namespace SS6\ShopBundle\Model\Seo;

use SS6\ShopBundle\Model\Setting\Setting;

class SeoSettingFacade {

	const SEO_TITLE_MAIN_PAGE = 'seoTitleMainPage';
	const SEO_TITLE_ADD_ON = 'seoTitleAddOn';
	const SEO_META_DESCRIPTION_MAIN_PAGE = 'seoMetaDescriptionMainPage';

	/**
	 * @var \SS6\ShopBundle\Model\Setting\Setting
	 */
	private $setting;

	public function __construct(Setting $setting) {
		$this->setting = $setting;
	}

	/**
	 * @param int $domainId
	 * @return string
	 */
	public function getTitleMainPage($domainId) {
		return $this->setting->get(self::SEO_TITLE_MAIN_PAGE, $domainId);
	}

	/**
	 * @param int $domainId
	 * @return string
	 */
	public function getDescriptionMainPage($domainId) {
		return $this->setting->get(self::SEO_META_DESCRIPTION_MAIN_PAGE, $domainId);
	}

	/**
	 * @param int $domainId
	 * @return string
	 */
	public function getTitleAddOn($domainId) {
		return $this->setting->get(self::SEO_TITLE_ADD_ON, $domainId);
	}

	/**
	 * @param string $value
	 * @param int $domainId
	 */
	public function setTitleMainPage($value, $domainId) {
		$this->setting->set(self::SEO_TITLE_MAIN_PAGE, $value, $domainId);
	}

	/**
	 * @param string $value
	 * @param int $domainId
	 */
	public function setDescriptionMainPage($value, $domainId) {
		$this->setting->set(self::SEO_META_DESCRIPTION_MAIN_PAGE, $value, $domainId);
	}

	/**
	 * @param string $value
	 * @param int $domainId
	 */
	public function setTitleAddOn($value, $domainId) {
		$this->setting->set(self::SEO_TITLE_ADD_ON, $value, $domainId);
	}

}