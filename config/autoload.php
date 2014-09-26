<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Aggregator
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Aggregator',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Elements
	'Aggregator\ContentAggregator'  => 'system/modules/aggregator/elements/ContentAggregator.php',

	// Classes
	'Aggregator\TwitterAPIExchange' => 'system/modules/aggregator/classes/TwitterAPIExchange.php',
	'Aggregator\AggregatorEngine'   => 'system/modules/aggregator/classes/AggregatorEngine.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'ce_aggregator' => 'system/modules/aggregator/templates',
	'aggregator_facebook' => 'system/modules/aggregator/templates',
	'aggregator_twitter' => 'system/modules/aggregator/templates',
	'aggregator_instagram' => 'system/modules/aggregator/templates'
));
