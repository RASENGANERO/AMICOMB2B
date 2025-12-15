<?

namespace {
	if (!defined('VENDOR_PARTNER_NAME')) {
		/** @const Aspro partner name */
		define('VENDOR_PARTNER_NAME', 'aspro');
	}

	if (!defined('VENDOR_SOLUTION_NAME')) {
		/** @const Aspro solution name */
		define('VENDOR_SOLUTION_NAME', 'max');
	}

	if (!defined('VENDOR_MODULE_ID')) {
		/** @const Aspro module id */
		define('VENDOR_MODULE_ID', 'aspro.max');
	}

	foreach ([
		'CMax' => 'TSolution',
		'CMaxCache' => \TSolution\Cache::class,
		'CMaxCondition' => \TSolution\Condition::class,
		'CMaxEvents' => \TSolution\Events::class,
		'CMaxRegionality' => \TSolution\Regionality::class,
		'Aspro\Functions\CAsproMax' => \TSolution\Functions::class,
		'Aspro\Max\Functions\Extensions' => \TSolution\Extensions::class,
		'Aspro\Max\Social\Factory' => \TSolution\Social\Factory::class,
		'Aspro\Max\Social\Video\Factory' => \TSolution\Social\Video\Factory::class,
		'Aspro\Max\Utils' => \Tsolution\Utils::class,
		'Aspro\Max\Filter' => \TSolution\Filter::class,
        'Aspro\Max\CacheableUrl' => \TSolution\CacheableUrl::class,
	] as $original => $alias) {
		if (!class_exists($alias)) {
			class_alias($original, $alias);
		}
	}

	// these alias declarations for IDE only
	if (false) {
		class TSolution extends CMax {}
	}
}

// these alias declarations for IDE only
namespace TSolution {
	if (false) {
		class Cache extends \CMaxCache {}
		class Condition extends \CMaxCondition {}
		class Events extends \CMaxEvents {}
		class Functions extends \Aspro\Functions\CAsproMax {}
		class Extensions extends \Aspro\Max\Functions\Extensions {}
		class Regionality extends \CMaxRegionality {}
		class Utils extends \Aspro\Max\Utils {}
		class Filter extends \Aspro\Max\Filter {}
        class CacheableUrl extends \Aspro\Max\CacheableUrl {}
	}
}

namespace TSolution\Social {
	if (false) {
		class Factory extends \Aspro\Max\Social\Factory {}
	}
}

namespace TSolution\Social\Video {
	if (false) {
		class Factory extends \Aspro\Max\Social\Video\Factory {}
	}
}
