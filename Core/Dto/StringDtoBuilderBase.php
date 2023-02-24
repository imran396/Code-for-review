<?php
/**
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           3/22/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Dto;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Data\TypeCast\Cast;

/**
 * Class DtoBuilder
 * @package
 */
abstract class StringDtoBuilderBase extends CustomizableClass
{
    /**
     * @param string $cid
     * @param array $body
     * @param string|null $filter
     * @return string|null
     */
    protected function readString(string $cid, array $body, ?string $filter = null): ?string
    {
        return Cast::toString($body[$cid] ?? null, $filter);
    }

    /**
     * @param string $cid
     * @param array $body
     * @return string|null
     */
    protected function readCheckbox(string $cid, array $body): ?string
    {
        return (string)$this->readString($cid, $body);
    }
}
