<?php
/**
 * Value-object contains parsed parts of lot item#, i.e. item num, item num extension
 *
 * SAM-5766: Move lot#,item#,sale# parser classes to new namespace
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\LotItem\ItemNo\Parse;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemNoParseResult
 * @package Sam\Core\LotItem\ItemNo
 */
class LotItemNoParsed extends CustomizableClass
{
    public readonly ?int $itemNum;
    public readonly string $itemNumExtension;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $itemNum
     * @param string $itemNumExtension
     * @return $this
     */
    public function construct(?int $itemNum = null, string $itemNumExtension = ''): static
    {
        $this->itemNum = $itemNum;
        $this->itemNumExtension = $itemNumExtension;
        return $this;
    }

    public function constructEmpty(): static
    {
        return $this->construct();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [$this->itemNum, $this->itemNumExtension];
    }

    /**
     * Check, item# is applicable
     * @return bool
     */
    public function ok(): bool
    {
        return $this->itemNum > 0;
    }
}
