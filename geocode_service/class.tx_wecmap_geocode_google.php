<?php
/***************************************************************
* Copyright notice
*
* (c) 2005-2009 Christian Technology Ministries International Inc.
* All rights reserved
* (c) 2011-2013 Jan Bartels, j.bartels@arcor.de, Google API V3
*
* parts from static_info_tables:
*  (c) 2013 Stanislas Rolland <typo3(arobas)sjbr.ca>
*
* This file is part of the Web-Empowered Church (WEC)
* (http://WebEmpoweredChurch.org) ministry of Christian Technology Ministries
* International (http://CTMIinc.org). The WEC is developing TYPO3-based
* (http://typo3.org) free software for churches around the world. Our desire
* is to use the Internet to help offer new life through Jesus Christ. Please
* see http://WebEmpoweredChurch.org/Jesus.
*
* You can redistribute this file and/or modify it under the terms of the
* GNU General Public License as published by the Free Software Foundation;
* either version 2 of the License, or (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This file is distributed in the hope that it will be useful for ministry,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the file!
***************************************************************/

/**
 * Service providing lat/long lookup via the Google Maps web service.
 */
class tx_wecmap_geocode_google extends \TYPO3\CMS\Core\Service\AbstractService {
	var $prefixId = 'tx_wecmap_geocode_google';
	var $scriptRelPath = 'geocode_service/class.tx_wecmap_geocode_google.php';
	var $extKey = 'wec_map';

	/**
	 * Returns the type of an iso code: nr, 2, 3
	 * code copied from static_info_tables
	 *
	 * @param	string	$isoCode	iso code
	 * @return	string		iso code type
	 */
	protected static function isoCodeType ($isoCode) {
		$type = '';
		$isoCodeAsInteger = \TYPO3\CMS\Core\Utility\MathUtility::canBeInterpretedAsInteger($isoCode);
		if ($isoCodeAsInteger) {
			$type = 'nr';
		} else if (strlen($isoCode) == 2) {
			$type = '2';
		} else if (strlen($isoCode) == 3) {
			$type = '3';
		}
		return $type;
	}

	/**
	 * Get a list of countries by specific parameters or parts of names of countries
	 * in different languages. Parameters might be left empty.
	 * code copied from static_info_tables
	 *
	 * @param	string	$country	a name of the country or a part of it in any language
	 * @param	string	$iso2	ISO alpha-2 code of the country
	 * @param	string	$iso3	ISO alpha-3 code of the country
	 * @param	array	$isonr	Database row.
	 * @return	array		Array of rows of country records
	 */
	protected static function fetchCountries ($country, $iso2='', $iso3='', $isonr='') {
		$rcArray = array();
		$where = '';

		$table = 'static_countries';
		if ($country != '') {
			$value = $GLOBALS['TYPO3_DB']->fullQuoteStr(trim('%'.$country.'%'),$table);
			$where = 'cn_official_name_local LIKE '.$value.' OR cn_official_name_en LIKE '.$value.' OR cn_short_local LIKE '.$value;
		}

		if ($isonr != '') {
			$where = 'cn_iso_nr='.$GLOBALS['TYPO3_DB']->fullQuoteStr(trim($isonr),$table);
		}

		if ($iso2 != '') {
			$where = 'cn_iso_2='.$GLOBALS['TYPO3_DB']->fullQuoteStr(trim($iso2),$table);
		}

		if ($iso3 !='') {
			$where = 'cn_iso_3='.$GLOBALS['TYPO3_DB']->fullQuoteStr(trim($iso3),$table);
		}

		if ($where != '') {
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);

			if ($res) {
				while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
					$rcArray[] = $row;
				}
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($res);
		}
		return $rcArray;
	}

	/**
	 * Performs an address lookup using the google web service.
	 *
	 * @param	string $street	The street address.
	 * @param	string $city	The city name.
	 * @param	string $state	The state name.
	 * @param	string $zip	The ZIP code.
	 * @param	string $country	Optional API key for accessing third party geocoder.
	 * @return	array $key		Array containing latitude and longitude.  If lookup failed, empty array is returned.
	 */
	function lookup($street, $city, $state, $zip, $country, $key='')	{

		$addressString = '';
		$region = '';

		if ( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('static_info_tables') )
		{
			// format address for Google search based on local address-format for given $country
			// convert $country to ISO3
			$countryCodeType = self::isoCodeType($country);
			if       ($countryCodeType == 'nr') {
				$countryArray = self::fetchCountries('', '', '', $country);
			} elseif ($countryCodeType == '2') {
				$countryArray = self::fetchCountries('', $country, '', '');
			} elseif ($countryCodeType == '3') {
				$countryArray = self::fetchCountries('', '', $country, '');
			} else {
				global $TYPO3_DB;

				$where = '';

				$table = 'static_countries';
				if ($country != '')	{
					$value = $TYPO3_DB->fullQuoteStr(trim($country),$table);
					$where = 'cn_official_name_local='.$value.' OR cn_official_name_en='.$value.' OR cn_short_local='.$value.' OR cn_short_en='.$value;

					$res = $TYPO3_DB->exec_SELECTquery('*', $table, $where);

					if ($res)	{
						while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))	{
							$countryArray[] = $row;
						}
					}
					$GLOBALS['TYPO3_DB']->sql_free_result($res);
				}

				if ( !is_array( $countryArray ) ) {
					$countryArray = self::fetchCountries($country, '', '', '');
				}
			}

			if(TYPO3_DLOG) {
				\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3: countryArray for '.$country, 'wec_map_geocode', -1, $countryArray);
			}

			if ( is_array( $countryArray ) && count( $countryArray ) == 1 )
			{
				$country = $countryArray[0]['cn_iso_3'];
				$region = $countryArray[0]['cn_tldomain'];
			}

			// format address accordingly
			$addressString = $this->formatAddress(',', $street, $city, $zip, $state, $country);  // $country: alpha-3 ISO-code (e. g. DEU)
			if(TYPO3_DLOG) {
				\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3 addressString', 'wec_map_geocode', -1, array( street => $street, city => $city, zip => $zip, state => $state, country => $country, addressString => $addressString ) );
			}
		}

		if ( !$addressString )
		{
			$addressString = $street.' '.$city.', '.$state.' '.$zip.', '.$country;	// default: US-format
		}

		// build URL
		$lookupstr = trim( $addressString );

		$url = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=' . urlencode( $lookupstr );
		if ( $region )
			$url .= '&region=' . urlencode( $region );

		// request Google-service and parse JSON-response
		if(TYPO3_DLOG) {
			\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3: URL '.$url, 'wec_map_geocode', -1 );
		}

		$attempt = 1;
		do {
			$jsonstr = \TYPO3\CMS\Core\Utility\GeneralUtility::getURL($url);

			$response_obj = json_decode( $jsonstr, true );
			if(TYPO3_DLOG) {
				\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3: '.$jsonstr, 'wec_map_geocode', -1, $response_obj);
			}
				if ($response_obj['status'] == 'OVER_QUERY_LIMIT')
					sleep(2);

			$attempt++;
		} while ($attempt <= 3 && $response_obj['status'] == 'OVER_QUERY_LIMIT');

		$latlong = array();
		if(TYPO3_DLOG) {
			$addressArray = array(
				'street' => $street,
				'city' => $city,
				'state' => $state,
				'zip' => $zip,
				'country' => $country,
				'region' => $region
			);
			\TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3: '.$addressString, 'wec_map_geocode', -1, $addressArray);
		}

		if ( $response_obj['status'] == 'OK' )
		{
			/*
			 * Geocoding worked!
			 */
			if (TYPO3_DLOG) \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3 Answer successful', 'wec_map_geocode', -1 );
			$latlong['lat'] = floatval( $response_obj['results'][0]['geometry']['location']['lat'] );
			$latlong['long'] = floatval( $response_obj['results'][0]['geometry']['location']['lng'] );
			if (TYPO3_DLOG) \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3 Answer', 'wec_map_geocode', -1, $latlong);
		}
		else if (  $response_obj['status'] == 'REQUEST_DENIED'
		        || $response_obj['status'] == 'INVALID_REQUEST'
		        )
		{
			/*
			 * Geocoder can't run at all, so disable this service and
			 * try the other geocoders instead.
			 */
			if (TYPO3_DLOG) \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3: '.$response_obj['status'].': '.$addressString.'. Disabling.', 'wec_map_geocode', 3 );
			$this->deactivateService();
			$latlong = null;
		}
		else
		{
			/*
			 * Something is wrong with this address. Might work for other
			 * addresses though.
			 */
			if (TYPO3_DLOG) \TYPO3\CMS\Core\Utility\GeneralUtility::devLog('Google V3: '.$response_obj['status'].': '.$addressString.'. Disabling.', 'wec_map_geocode', 2 );
			$latlong = null;
		}

		return $latlong;
	}


	/**
	 * Formatting an address in the format specified
	 *
	 * @param	string	$delim	A delimiter for the fields of the returned address
	 * @param	string	$streetAddress	A street address
	 * @param	string	$city	A city
	 * @param	string	$zip	A zip code
	 * @param	string	$subdivisionCode	A ISO alpha-3 country code (cn_iso_3)
	 * @param	string	$countryCode	A zip code	A country subdivision code (zn_code)
	 * @return	string		The formated address using the country address format (cn_address_format)
	 */
	function formatAddress ($delim, $streetAddress, $city, $zip, $subdivisionCode='', $countryCode='')	{

		if(TYPO3_MODE == 'FE')
		{
			/** @var \SJBR\StaticInfoTables\PiBaseApi $staticInfoObj */
			$staticInfoObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\SJBR\StaticInfoTables\PiBaseApi::class);
			if ($staticInfoObj->needsInit()) {
				$staticInfoObj->init();
			}
			return $staticInfoObj->formatAddress($delim, $streetAddress, $city, $zip, $subdivisionCode, $countryCode);
		}

		$conf = $this->loadTypoScriptForBEModule('tx_staticinfotables_pi1');

		global $TYPO3_DB;

		$formatedAddress = '';
		$countryCode = ($countryCode ? trim($countryCode) : $this->defaultCountry);
		$subdivisionCode = ($subdivisionCode ? trim($subdivisionCode) : ($countryCode == $this->defaultCountry ? $this->defaultCountryZone : ''));

		// Get country name
		$countryName = \SJBR\StaticInfoTables\Utility\LocalizationUtility::getLabelFieldValue($countryCode, 'static_countries', '', FALSE);
		if (!$countryName) {
			return $formatedAddress;
		}

			// Get address format
		$res = $TYPO3_DB->exec_SELECTquery(
			'cn_address_format',
			'static_countries',
			'cn_iso_3='.$TYPO3_DB->fullQuoteStr($countryCode,'static_countries')
		);
		$row = $TYPO3_DB->sql_fetch_assoc($res);
		$TYPO3_DB->sql_free_result($res);
		$addressFormat = $row['cn_address_format'];

		// Format the address
		$formatedAddress = $conf['addressFormat.'][$addressFormat];
		$formatedAddress = str_replace('%street', $streetAddress, $formatedAddress);
		$formatedAddress = str_replace('%city', $city, $formatedAddress);
		$formatedAddress = str_replace('%zip', $zip, $formatedAddress);
		$formatedAddress = str_replace('%countrySubdivisionCode', $subdivisionCode, $formatedAddress);
		$formatedAddress = str_replace('%countrySubdivisionName', $subdivisionCode, $formatedAddress);
		$formatedAddress = str_replace('%countryName', strtoupper($countryName), $formatedAddress);
		$formatedAddress = implode($delim, \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(';', $formatedAddress, 1));

		return $formatedAddress;
	}


	/**
	 * Loads the TypoScript for the given extension prefix, e.g. tx_cspuppyfunctions_pi1, for use in a backend module.
	 *
	 * @param string $extKey
	 * @return array
	 */
	function loadTypoScriptForBEModule($extKey) {
		list($page) = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecordsByField('pages', 'pid', 0);
		$pageUid = intval($page['uid']);
		/** @var \TYPO3\CMS\Frontend\Page\PageRepository $sysPageObj */
		$sysPageObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Frontend\Page\PageRepository::class);
		$rootLine = $sysPageObj->getRootLine($pageUid);

		/** @var \TYPO3\CMS\Core\TypoScript\ExtendedTemplateService $TSObj */
		$TSObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\ExtendedTemplateService::class);
		$TSObj->tt_track = 0;
		$TSObj->init();
		$TSObj->runThroughTemplates($rootLine);
		$TSObj->generateConfig();
		return $TSObj->setup['plugin.'][$extKey . '.'];
	}

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_map/geocode_service/class.tx_wecmap_geocode_google.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_map/geocode_service/class.tx_wecmap_geocode_google.php']);
}
