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

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{aggregator_legend:hide},aggregator_blacklist,aggregator_facebook_app_id,aggregator_faceboook_app_secret,aggregator_twitter_api_key,aggregator_twitter_api_secret,aggregator_twitter_access_token,aggregator_twitter_access_token_secret,aggregator_instagram_client_id,aggregator_instagram_client_secret,aggregator_facebook_cache,aggregator_twitter_cache,aggregator_instagram_cache';

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_blacklist'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_blacklist'],
	'inputType'	=> 'text',
	'eval'		=> array('tl_class'=>'long')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_facebook_app_id'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_facebook_app_id'],
	'inputType'	=> 'text',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50 m12')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_faceboook_app_secret'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_faceboook_app_secret'],
	'inputType'	=> 'text',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50 m12')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_twitter_access_token'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_twitter_access_token'],
	'inputType'	=> 'text',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_twitter_access_token_secret'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_twitter_access_token_secret'],
	'inputType'	=> 'text',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_twitter_api_key'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_twitter_api_key'],
	'inputType'	=> 'text',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_twitter_api_secret'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_twitter_api_secret'],
	'inputType'	=> 'text',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_instagram_client_id'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_instagram_client_id'],
	'inputType'	=> 'text',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50 m12')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_instagram_client_secret'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_instagram_client_secret'],
	'inputType'	=> 'text',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50 m12')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_facebook_cache'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_facebook_cache'],
	'inputType'	=> 'text',
	'default'	=> '120',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50 m12'),
	'load_callback'	=>	array(array('AggregatorEngine', 'convertToMinutes')),
	'save_callback'	=>	array(array('AggregatorEngine', 'convertToSeconds'))
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_twitter_cache'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_twitter_cache'],
	'inputType'	=> 'text',
	'default'	=> '900',
	'eval'		=> array('nospace'=>true, 'tl_class'=>'w50 m12'),
	'load_callback'	=>	array(array('AggregatorEngine', 'convertToMinutes')),
	'save_callback'	=>	array(array('AggregatorEngine', 'convertToSeconds'))
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['aggregator_instagram_cache'] = array(
	'label'		=> &$GLOBALS['TL_LANG']['tl_settings']['aggregator_instagram_cache'],
	'inputType'	=> 'text',
	'default'	=> '300',
	'eval'		=> array('nospace'=>true, 'rgxp'=>'digit', 'tl_class'=>'w50'),
	'load_callback'	=>	array(array('AggregatorEngine', 'convertToMinutes')),
	'save_callback'	=>	array(array('AggregatorEngine', 'convertToSeconds'))
);