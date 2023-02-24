<?php
/**
 * Value-object contains parsed parts of auction sale#, i.e. sale num, sale num extension
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

namespace Sam\Core\Auction\SaleNo\Parse;

use Sam\Core\Service\CustomizableClass;

/**
 * Class SaleNoParseResult
 * @package Sam\Core\Auction\SaleNo\Parse
 */
class SaleNoParsed extends CustomizableClass
{
    /**
     * @var int|null
     */
    public ?int $saleNum;
    /**
     * @var string
     */
    public string $saleNumExtension;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $saleNum
     * @param string $saleNumExtension
     * @return $this
     */
    public function construct(?int $saleNum = null, string $saleNumExtension = ''): static
    {
        $this->saleNum = $saleNum;
        $this->saleNumExtension = $saleNumExtension;
        return $this;
    }

    /**
     * @return array{0: int|null, 1: string}
     */
    public function toArray(): array
    {
        return [$this->saleNum, $this->saleNumExtension];
    }

    /**
     * Check, if sale# is applicable
     * @return bool
     */
    public function ok(): bool
    {
        return $this->saleNum > 0;
    }
}
