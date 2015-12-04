<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
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
	// Classes
	'Aggregator\AggregatorEngine'   => 'system/modules/aggregator/classes/AggregatorEngine.php',
	'Aggregator\TwitterAPIExchange' => 'system/modules/aggregator/classes/TwitterAPIExchange.php',

	// Elements
	'Aggregator\ContentAggregator'  => 'system/modules/aggregator/elements/ContentAggregator.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'aggregator_facebook'  => 'system/modules/aggregator/templates',
	'aggregator_instagram' => 'system/modules/aggregator/templates',
	'aggregator_twitter'   => 'system/modules/aggregator/templates',
	'ce_aggregator'        => 'system/modules/aggregator/templates',
));
