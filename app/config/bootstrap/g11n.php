<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\g11n\Catalog;
use \lithium\g11n\Message;
use \lithium\net\http\Media;

/**
 * Globalization (g11n) catalog configuration.  The catalog allows for obtaining and
 * writing globalized data. Each configuration can be adjusted through the following settings:
 *
 *   - `'adapter' The name of a supported adapter. The builtin adapters are _memory_ (a
 *     simple adapter good for runtime data and testing), _gettext_, _cldr_ (for
 *     interfacing with Unicode's common locale data repository) and _code_ (used mainly for
 *     extracting message templates from source code).
 *
 *   - `'path'` All adapters with the exception of the _memory_ adapter require a directory
 *     which holds the data.
 *
 *   - `'scope'` If you plan on using scoping i.e. for accessing plugin data separately you
 *     need to specify a scope for each configuration, except for those using the _memory_,
 *     _php_ or _gettext_ adapter which handle this internally.
 */
Catalog::config(array(
	'runtime' => array(
		'adapter' => 'Memory'
	),
// 	'app' => array(
// 		'adapter' => 'Gettext',
// 		'path' => LITHIUM_APP_PATH . '/resources/g11n'
// 	),
	'lithium' => array(
		'adapter' => 'Php',
		'path' => LITHIUM_LIBRARY_PATH . '/lithium/g11n/resources/php'
	)
));

/**
 * Globalization runtime data.  You can add globalized data during runtime utilizing a
 * configuration set up to use the _memory_ adapter.
 */
$data = function($n) { return $n != 1 ? 1 : 0; };
Catalog::write('message.plural', 'root', $data, array('name' => 'runtime'));

/**
 * Embeds message translation short-hands into the `View` class (or other content handler,
 * if specified) when content is rendered. This enables short-hand translation functions, i.e.
 * `<?=$t("Translated content"); ?>`.
 */
Media::applyFilter('_handle', function($self, $params, $chain) {
	$params['handler'] += array('outputFilters' => array());
	$params['handler']['outputFilters'] += Message::shortHands();
	return $chain->next($self, $params, $chain);
});

/*
 * Inflector configuration examples.  If your application has custom singular or plural rules, or
 * extra non-ASCII characters to transliterate, you can configure that by uncommenting the lines
 * below.
 */
// use lithium\util\Inflector;
//
// Inflector::rules('singular', array('rules' => array('/rata/' => '\1ratus')));
// Inflector::rules('singular', array('irregular' => array('foo' => 'bar')));
//
// Inflector::rules('plural', array('rules' => array('/rata/' => '\1ratum')));
// Inflector::rules('plural', array('irregular' => array('bar' => 'foo')));
//
// Inflector::rules('transliteration', array('/É|Ê/' => 'E'));
//
// Inflector::rules('uninflected', 'bord');
// Inflector::rules('uninflected', array('bord', 'baird'));

/**
 * Enabling globalization integration.  Classes in the framework are designed with
 * globalization in mind. To enable globalization for these classes we just need to pass
 * the needed data into them.
 */
// use lithium\util\Validator;
// use lithium\util\Inflector;
//
// Validator::add('phone', Catalog::read('validation.phone', 'en_US'));
// Inflector::rules('transliteration', Catalog::read('inflection.transliteration', 'en'));

?>