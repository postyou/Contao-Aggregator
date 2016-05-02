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
 * Back end modules
 */
$GLOBALS['BE_MOD']['content']['aggregator'] = array(
	'tables' => array('tl_aggregator'),
	'icon'   => 'system/modules/aggregator/assets/icon.gif'
);

/**
 * Content elements
 */
 $GLOBALS['TL_CTE']['includes']['aggregator'] = 'ContentAggregator';
 
 /**
  * Register Cronjob
  */
$GLOBALS['TL_CRON']['minutely'][] = array('AggregatorEngine', 'checkForAggregatorUpdates');
$GLOBALS['TL_CRON']['hourly'][] = array('IntegrateContentToNewsEngine', 'insertCacheDataToNewsDB');


if(TL_MODE == 'BE')
    $GLOBALS['TL_CSS'][] = '/system/modules/aggregator/assets/css/backend.css';


$GLOBALS['TL_HOOKS']['parseArticles'][] = array('NewsListAggregatorHook', 'parseFacebookPosts');
$GLOBALS['TL_HOOKS']['newsListFetchItems'][] = array('NewsListAggregatorHook', 'filterFacebookPosts');