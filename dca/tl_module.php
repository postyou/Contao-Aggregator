<?php

$GLOBALS['TL_DCA']['tl_module']['palettes']['newslist'] = str_replace(';{template_legend',',hideFacebookNews,text_only_mode,messageLength;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newslist']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader'] = str_replace(';{template_legend',',hideFacebookNews,text_only_mode,messageLength;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newsreader']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsarchive'] = str_replace(';{template_legend',',hideFacebookNews,text_only_mode,messageLength;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newsarchive']);
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsmenu'] = str_replace(';{template_legend',',hideFacebookNews,text_only_mode,messageLength;{template_legend', $GLOBALS['TL_DCA']['tl_module']['palettes']['newsmenu']);

$GLOBALS['TL_DCA']['tl_module']['fields']['hideFacebookNews'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hideFacebookNews'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'					  => array('tl_class' => 'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['text_only_mode'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['text_only_mode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['messageLength'] = array(
    'label'     		=> &$GLOBALS['TL_LANG']['tl_aggregator']['messageLength'],
    'inputType' 		=> 'text',
    'default'			=> '160',
    'eval'      		=> array(
        'mandatory'   => true
    ),
    'sql'            => "varchar(255) NOT NULL default '160'"
);