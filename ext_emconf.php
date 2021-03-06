<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'WEC Map',
	'description' => 'Mapping extension that connects to geocoding databases and Google Maps API.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '3.2.0',
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
	'author' => 'Web-Empowered Church Team (V1.x, V2.x), Jan Bartels (V3.x)',
	'author_email' => 'j.bartels@arcor.de',
	'author_company' => 'Christian Technology Ministries International Inc. (V1.x, V2.x)',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.5.0-0.0.0',
			'typo3' => '6.2.0-7.99.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
			'tt_address'         => '2.3.4-0.0.0',
			'nn_address'         => '2.3.0-0.0.0',
            'sr_feuser_register' => '',
		),
	),
	'suggests' => array(
	),
);
