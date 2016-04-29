<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace('{config_legend},news_archives,numberOfItems,news_featured,perPage,skipFirst;','{config_legend},news_archives,numberOfItems,news_featured,perPage,skipFirst, hideFacebookNews;', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);

$GLOBALS['TL_DCA']['tl_module']['fields']['hideFacebookNews'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hideFacebookNews'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'					  => array('tl_class' => 'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);