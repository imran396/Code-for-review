<?php
/**
 * Value-object contains parsed parts of auction lot#, i.e. lot num, lot num extension, lot num prefix
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

namespace Sam\Core\AuctionLot\LotNo\Parse;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotNoParseResult
 * @package Sam\Core\AuctionLot\LotNo\Parse
 */
class LotNoParsed extends CustomizableClass
{
    /**
     * @var int|null
     */
    public ?int $lotNum;
    /**
     * @var string
     */
    public string $lotNumPrefix;
    /**
     * @var string
     */
    public string $lotNumExtension;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $lotNum
     * @param string $lotNumExtension
     * @param string $lotNumPrefix
     * @return $this
     */
    public function construct(?int $lotNum = null, string $lotNumExtension = '', string $lotNumPrefix = ''): static
    {
        $this->lotNum = $lotNum;
        $this->lotNumExtension = $lotNumExtension;
        $this->lotNumPrefix = $lotNumPrefix;
        return $this;
    }

    public function constructEmpty(): static
    {
        return $this->construct();
    }

    /**
     * @return array [<lot num>, <lot num extension>, <lot num prefix>]
     */
    public function toArray(): array
    {
        return [$this->lotNum, $this->lotNumExtension, $this->lotNumPrefix];
    }

    public function logData(): array
    {
        return [
            'lotNum' => $this->lotNum,
            'lotNumExt' => $this->lotNumExtension,
            'lotNumPrefix' => $this->lotNumPrefix,
        ];
    }

    /**
     * Check, lot# is applicable
     * @return bool
     */
    public function ok(): bool
    {
        return $this->lotNum !== null
            && $this->lotNum >= 0;
    }
}
