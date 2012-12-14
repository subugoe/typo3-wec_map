<?php

########################################################################
# Extension Manager/Repository config file for ext "wec_map".
#
# Auto generated 29-11-2011 10:12
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'WEC Map',
	'description' => 'Mapping extension that connects to geocoding databases and Google Maps API.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '2.4.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => 'bottom',
	'loadOrder' => '',
	'module' => 'mod1,mod2',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Web-Empowered Church Team',
	'author_email' => 'map@webempoweredchurch.org',
	'author_company' => 'Christian Technology Ministries International Inc.',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '3.0.0-0.0.0',
			'typo3' => '4.3.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:73:{s:9:"CHANGELOG";s:4:"7cfe";s:27:"class.tx_wecmap_backend.php";s:4:"eea3";s:32:"class.tx_wecmap_batchgeocode.php";s:4:"2dc6";s:25:"class.tx_wecmap_cache.php";s:4:"fba6";s:29:"class.tx_wecmap_domainmgr.php";s:4:"86cb";s:23:"class.tx_wecmap_map.php";s:4:"bcc3";s:26:"class.tx_wecmap_marker.php";s:4:"db8a";s:31:"class.tx_wecmap_markergroup.php";s:4:"8e8e";s:26:"class.tx_wecmap_shared.php";s:4:"1b8f";s:21:"ext_conf_template.txt";s:4:"b513";s:12:"ext_icon.gif";s:4:"91f0";s:17:"ext_localconf.php";s:4:"41b6";s:14:"ext_tables.php";s:4:"2c69";s:14:"ext_tables.sql";s:4:"9080";s:16:"locallang_db.xml";s:4:"4833";s:7:"tca.php";s:4:"6117";s:29:"contrib/tablesort/fastinit.js";s:4:"afbd";s:30:"contrib/tablesort/tablesort.js";s:4:"c6e0";s:24:"csh/locallang_csh_ff.xml";s:4:"c0b3";s:14:"doc/manual.sxw";s:4:"eec7";s:52:"geocode_service/class.tx_wecmap_geocode_geocoder.php";s:4:"782a";s:50:"geocode_service/class.tx_wecmap_geocode_google.php";s:4:"700c";s:52:"geocode_service/class.tx_wecmap_geocode_worldkit.php";s:4:"100e";s:49:"geocode_service/class.tx_wecmap_geocode_yahoo.php";s:4:"cd28";s:14:"images/aai.gif";s:4:"03ce";s:20:"images/icon_home.gif";s:4:"6e80";s:27:"images/icon_home_shadow.png";s:4:"ce1c";s:20:"images/mm_20_red.png";s:4:"453d";s:23:"images/mm_20_shadow.png";s:4:"f77b";s:49:"map_service/google/class.tx_wecmap_map_google.php";s:4:"ee1c";s:52:"map_service/google/class.tx_wecmap_marker_google.php";s:4:"47ea";s:32:"map_service/google/locallang.xml";s:4:"54ac";s:47:"map_service/yahoo/class.tx_wecmap_map_yahoo.php";s:4:"a3bb";s:28:"map_service/yahoo/yahoo.tmpl";s:4:"a46c";s:42:"mod1/class.tx_wecmap_batchgeocode_util.php";s:4:"3f0c";s:38:"mod1/class.tx_wecmap_recordhandler.php";s:4:"9dc8";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"da73";s:14:"mod1/index.php";s:4:"bb46";s:18:"mod1/locallang.xml";s:4:"4746";s:22:"mod1/locallang_mod.xml";s:4:"5106";s:19:"mod1/moduleicon.gif";s:4:"1af1";s:34:"mod1/tx_wecmap_batchgeocode_ai.php";s:4:"579e";s:35:"mod1/tx_wecmap_recordhandler_ai.php";s:4:"0c33";s:14:"mod2/clear.gif";s:4:"cc11";s:13:"mod2/conf.php";s:4:"0f00";s:14:"mod2/index.php";s:4:"6656";s:18:"mod2/locallang.xml";s:4:"4a3f";s:22:"mod2/locallang_mod.xml";s:4:"341f";s:19:"mod2/moduleicon.gif";s:4:"4fd7";s:14:"pi1/ce_wiz.gif";s:4:"fa31";s:27:"pi1/class.tx_wecmap_pi1.php";s:4:"ab3b";s:35:"pi1/class.tx_wecmap_pi1_wizicon.php";s:4:"d189";s:19:"pi1/flexform_ds.xml";s:4:"e50b";s:17:"pi1/locallang.xml";s:4:"ae42";s:20:"pi1/static/setup.txt";s:4:"0286";s:14:"pi2/ce_wiz.gif";s:4:"4083";s:27:"pi2/class.tx_wecmap_pi2.php";s:4:"beb1";s:35:"pi2/class.tx_wecmap_pi2_wizicon.php";s:4:"a051";s:19:"pi2/flexform_ds.xml";s:4:"409c";s:17:"pi2/locallang.xml";s:4:"69e4";s:20:"pi2/static/setup.txt";s:4:"4529";s:14:"pi3/ce_wiz.gif";s:4:"e7bd";s:27:"pi3/class.tx_wecmap_pi3.php";s:4:"9b83";s:35:"pi3/class.tx_wecmap_pi3_wizicon.php";s:4:"d05c";s:19:"pi3/flexform_ds.xml";s:4:"9a9a";s:17:"pi3/locallang.xml";s:4:"1852";s:20:"pi3/static/setup.txt";s:4:"8bc8";s:40:"res/icon_tx_wecmap_external_resource.gif";s:4:"daf0";s:13:"res/wecmap.js";s:4:"ddf2";s:16:"static/setup.txt";s:4:"22d4";s:27:"tests/autozoom_testcase.php";s:4:"e11f";s:36:"tests/get_address_field_testcase.php";s:4:"390c";}',
	'suggests' => array(
	),
);

?>