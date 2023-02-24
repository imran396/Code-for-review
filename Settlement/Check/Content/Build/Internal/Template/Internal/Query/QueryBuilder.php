<?php
/**
 * Produce result set for selecting fields from DB.
 *
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Query;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Content\Build\Internal\Template\Internal\Config\PlaceholderConfig;

/**
 * Class QueryBuilder
 * @package Sam\Settlement\Check
 */
class QueryBuilder extends CustomizableClass
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
     * @param array $placeholders
     * @return array
     * #[Pure]
     */
    public function collectSelect(array $placeholders): array
    {
        $select = [];
        $placeholders = array_values($placeholders);
        $placeholders = array_unique($placeholders);
        $resultFields = [];
        foreach ($placeholders as $placeholder) {
            $resultFields = array_merge($resultFields, PlaceholderConfig::USER_PLACEHOLDERS_CONFIG[$placeholder]['select']);
        }
        $resultFields = array_unique($resultFields);
        foreach ($resultFields as $resultField) {
            $select[] = sprintf('%s AS %s', PlaceholderConfig::USER_RESULT_FIELDS[$resultField], $resultField);
        }
        return $select;
    }

}
