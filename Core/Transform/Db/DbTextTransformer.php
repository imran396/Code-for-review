<?php
/**
 * SAM-4445: Apply TextFormatter
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           05-22, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Transform\Db;

use Sam\Core\Service\CustomizableClass;

/**
 * Class DbTextTransformer
 * @package Sam\Core\Transform\Db
 */
class DbTextTransformer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $name
     * @return string
     */
    public function toDbColumn(string $name): string
    {
        $name = strtolower($name);
        $name = preg_replace('/[^0-9a-z]+/i', '_', $name);
        return trim($name, '_');
    }

    /**
     * Filter illegal characters from db field name
     *
     * @param string $name
     * @return string
     */
    public function filterDbColumn(string $name): ?string
    {
        $name = (string)preg_replace("/[^\w.`_]+/", '', $name);
        $name = (string)preg_replace(['/[_]+/', '/[`]+/', '/[.]+/'], ['_', '`', '.'], $name);
        return $name;
    }
}
