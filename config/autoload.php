<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Aggregator',
	'aggregator',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Aggregator\TwitterAPIExchange'           => 'system/modules/aggregator/classes/TwitterAPIExchange.php',
	'aggregator\IntegrateContentToNewsEngine' => 'system/modules/aggregator/classes/IntegrateContentToNewsEngine.php',
	'Aggregator\AggregatorEngine'             => 'system/modules/aggregator/classes/AggregatorEngine.php',
	'Aggregator\NewsModelAggregator'          => 'system/modules/aggregator/classes/NewsModelAggregator.php',
	'Aggregator\ModuleNewsListAggregator'     => 'system/modules/aggregator/classes/ModuleNewsListAggregator.php',

	// Hooks
	'NewsListAggregatorHook'                  => 'system/modules/aggregator/hooks/NewsListAggregatorHook.php',

	// Elements
	'Aggregator\ContentAggregator'            => 'system/modules/aggregator/elements/ContentAggregator.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'news_aggregator'      => 'system/modules/aggregator/templates',
	'aggregator_facebook'  => 'system/modules/aggregator/templates',
	'aggregator_instagram' => 'system/modules/aggregator/templates',
	'news_latest'          => 'system/modules/aggregator/templates',
	'aggregator_twitter'   => 'system/modules/aggregator/templates',
	'ce_aggregator'        => 'system/modules/aggregator/templates',
));
