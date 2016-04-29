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
 
/**
 * Table tl_aggregator
 */
$GLOBALS['TL_DCA']['tl_aggregator'] = array(
	'config'   => array(
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'sql'              => array(
			'keys' => array(
				'id' => 'primary'
			)
		),
	),
	
	'list'     => array(
		'sorting'           => array(
			'mode'        => 2,
			'fields'      => array('title'),
			'flag'        => 1,
			'panelLayout' => 'filter;sort,search,limit'
		),
		
		'label'             => array(
			'fields' => array('type', 'title'),
			'format' => '[%s] %s',
		),
		
		'global_operations' => array(
			'all' => array(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		
		'operations'        => array(
			'edit'   => array(
				'label' => &$GLOBALS['TL_LANG']['tl_aggregator']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'delete' => array(
				'label'      => &$GLOBALS['TL_LANG']['tl_aggregator']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show'   => array(
				'label'      => &$GLOBALS['TL_LANG']['tl_aggregator']['show'],
				'href'       => 'act=show',
				'icon'       => 'show.gif',
				'attributes' => 'style="margin-right:3px"'
			),
			'toggle'	=> array(
				'label'               => &$GLOBALS['TL_LANG']['tl_aggregator']['toggle'],
    			'icon'                => 'visible.gif',
    			'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
    			'button_callback'     => array('AggregatorEngine', 'toggleIcon')
			),
		)
	),
	'palettes' => array(
		'__selector__'  		=> array('type', 'addToNewsModul'),
		'default'       		=> '{title_legend},title,type;',
		'facebookUser'			=> '{title_legend},title,type;{source_legend},facebookUser,cache,badwords,published,addToNewsModul',
		'twitterUser'			=> '{title_legend},title,type;{source_legend},twitterUser,cache,badwords,published,addToNewsModul',
		'twitterHashtag'		=> '{title_legend},title,type;{source_legend},twitterHashtag,cache,badwords,published,addToNewsModul',
		'instagramUser'			=> '{title_legend},title,type;{source_legend},instagramUser,cache,badwords,published,addToNewsModul',
		'instagramHashtag'		=> '{title_legend},title,type;{source_legend},instagramHashtag,cache,badwords,published,addToNewsModul'
	),
	
	// Subpalettes
	'subpalettes' => array
	(
		'addToNewsModul' => 'infoText, selectNewsArchive'
	),

	'fields'   => array(
		'id'     => array(
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'lastUpdate'	=> array(
			'sql'	=> "int(10) unsigned NOT NULL default '0'"
		),
		'title'  => array(
			'label'     => &$GLOBALS['TL_LANG']['tl_aggregator']['title'],
			'inputType' => 'text',
			'exclude'   => true,
			'sorting'   => true,
			'flag'      => 1,
            'search'    => true,
			'eval'      => array(
								'mandatory'				=> true,
                            	'unique'				=> false,
                            	'maxlength'   			=> 255,
								'tl_class'				=> 'clr',
 							),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
        'type'    => array(
			'label'         => &$GLOBALS['TL_LANG']['tl_aggregator']['type'],
			'inputType' => 'select',
			'options'	=> array('facebookUser', 'twitterUser', 'twitterHashtag', 'instagramUser', 'instagramHashtag'),
			'reference'	=> &$GLOBALS['TL_LANG']['tl_aggregator']['options'],
			'exclude'     => true,
			'eval'      => array(
								'mandatory'   => true,
								'includeBlankOption'	=> true,
								'submitOnChange'    	=> true,
								'tl_class'    => 'clr',
 							),
			'sql'            => "varchar(255) NOT NULL"
		),
		'facebookUser'    => array(
			'label'         => &$GLOBALS['TL_LANG']['tl_aggregator']['facebookUser'],
			'inputType' => 'text',
			'search'	=> true,
			'exclude'     => true,
			'eval'      => array(
								'mandatory'   => true,
								'tl_class'    => 'w50',
 							),
			'sql'            => "varchar(255) NULL",
			'load_callback'	=> array(array('AggregatorEngine', 'getFacebookAlias')),
			'save_callback'	=> array(array('AggregatorEngine', 'getFacebookId'))
		),
		'twitterUser'    => array(
			'label'         => &$GLOBALS['TL_LANG']['tl_aggregator']['twitterUser'],
			'inputType' => 'text',
			'search'	=> true,
			'exclude'     => true,
			'eval'      => array(
								'mandatory'   => true,
								'tl_class'    => 'w50',
 							),
			'sql'            => "varchar(255) NULL",
			'save_callback'	=> array(array('AggregatorEngine', 'checkTwitterAlias'))
		),
		'twitterHashtag'    => array(
			'label'         => &$GLOBALS['TL_LANG']['tl_aggregator']['twitterHashtag'],
			'inputType' => 'text',
			'search'	=> true,
			'exclude'     => true,
			'eval'      => array(
								'mandatory'   => true,
								'tl_class'    => 'w50',
 							),
			'sql'            => "varchar(255) NULL",
			'save_callback'	=> array(array('AggregatorEngine', 'validateHashtag'))
		),
		'instagramUser'    => array(
			'label'         => &$GLOBALS['TL_LANG']['tl_aggregator']['instagramUser'],
			'inputType' => 'text',
			'search'	=> true,
			'exclude'     => true,
			'eval'      => array(
								'mandatory'   => true,
								'tl_class'    => 'w50',
 							),
			'sql'            => "varchar(255) NULL",
			'load_callback'	 => array(array('AggregatorEngine', 'getInstagramName')),
			'save_callback'	 => array(array('AggregatorEngine', 'getInstagramId'))
		),
		'instagramHashtag'    => array(
			'label'         => &$GLOBALS['TL_LANG']['tl_aggregator']['instagramHashtag'],
			'inputType' => 'text',
			'search'	=> true,
			'exclude'     => true,
			'eval'      => array(
								'mandatory'   => true,
								'tl_class'    => 'w50',
 							),
			'sql'            => "varchar(255) NULL",
			'save_callback'	=> array(array('AggregatorEngine', 'validateHashtag'))
		),
		'cache'		=> array(
			'label'		=>	&$GLOBALS['TL_LANG']['tl_aggregator']['specificCache'],
			'inputType'	=>	'text',
			'search'	=>	true,
			'exclude'	=>	true,
			'default'	=>  0,
			'eval'		=>	array(
								'mandatory'	=>	false,
								'tl_class'	=>	'w50',
								'rgxp'		=>	'digit'
							),
			'sql'		=>	"int(10) NOT NULL default '0'",
			'load_callback'	=>	array(array('AggregatorEngine', 'convertToMinutes')),
			'save_callback'	=>	array(array('AggregatorEngine', 'convertToSeconds'))
		),
		'badwords' => array(
			'label'     		=> &$GLOBALS['TL_LANG']['tl_aggregator']['badwords'],
			'inputType' 		=> 'text',
			'eval'      		=> array(
									'mandatory'   => false,
									'maxlength'	  => 512,
									'tl_class'    => 'long',
 									),
			'sql'           	=> "varchar(512) NULL"
		),
		'published'	=> array(
			'label'               => &$GLOBALS['TL_LANG']['tl_aggregator']['published'],
    		'exclude'             => true,
    		'filter'              => true,
    		'inputType'           => 'checkbox',
			'eval'      => array(
								'tl_class'    => 'm12',
 							),
    		'sql'                 => "char(1) NOT NULL default ''"
		),
		'addToNewsModul' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_aggregator']['addToNewsModul'],
			'exclude'			=> true,
			'inputType'			=> 'checkbox',
			'eval'				=> array('tl_class' => '', 'submitOnChange' => true),
    		'sql'				=> "char(1) NOT NULL default '1'"
		),

		'infoText' => array(
			'input_field_callback' => array('tl_aggregator', 'addInfoText')
		),

		'selectNewsArchive' => array(
			'label'				=> &$GLOBALS['TL_LANG']['tl_aggregator']['selectNewsArchive'],
			'exclude'			=> true,
			'inputType'			=> 'checkbox',
			'foreignKey'		=> 'tl_news_archive.title',
			'eval'				=> array('tl_class' => '', 'mandatory' => true, 'multiple' => true),
			'sql'				=> "BLOB NULL",
    		'relation'			=> array('type' => 'hasMany', 'load' => 'lazy')
		)
	)
);

class tl_aggregator extends Backend {

	public function addInfoText() {
		return '<div style=\'margin-top: 16px;\'><strong>'.$GLOBALS['TL_LANG']['tl_aggregator']['infoText'][0].' </strong><br>'.$GLOBALS['TL_LANG']['tl_aggregator']['infoText'][1].'</div>';
	}

}