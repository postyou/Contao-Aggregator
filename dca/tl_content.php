<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2014 Leo Feyer
 * 
 * @package   aggregator 
 * @author    Johannes Terhürne
 * @license   MIT License
 * @copyright Johannes Terhürne 2014 
 */

$GLOBALS['TL_DCA']['tl_content']['palettes']['aggregator'] = '{type_legend},type,headline;{aggregatorContent_legend},channels,refresh,numPosts,messageLength;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';

$GLOBALS['TL_DCA']['tl_content']['fields']['channels'] = array(
	'label'     		=> &$GLOBALS['TL_LANG']['tl_aggregator']['channels'],
	'inputType' 		=> 'checkbox',
	'options_callback' 	=> array('AggregatorEngine', 'getAllChannels'),
	'eval'				=> array(
								'mandatory'	=>	true,
								'multiple' 	=> 	true,
							),
	'sql'				=> "blob NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['numPosts'] = array(
	'label'     		=> &$GLOBALS['TL_LANG']['tl_aggregator']['numPosts'],
	'inputType' 		=> 'text',
	'default'			=> 6,
	'eval'      		=> array(
								'mandatory'   => true
 							),
	'sql'            => "int(10) NOT NULL"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['messageLength'] = array(
	'label'     		=> &$GLOBALS['TL_LANG']['tl_aggregator']['messageLength'],
	'inputType' 		=> 'text',
	'default'			=> '160',
	'eval'      		=> array(
								'mandatory'   => true
 							),
	'sql'            => "varchar(255) NOT NULL default '160'"
);