<?php
/**
 * SAM-5739: RTB ping
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\ReversePing;


use Sam\Core\Service\CustomizableClass;

/**
 * Class ReversePingCommand
 * @package Sam\Rtb\Command\Concrete\ReversePing
 */
class ReversePingCommand extends CustomizableClass
{
    protected ?float $timestamp;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param float|null $timestamp
     * @return static
     */
    public function construct(?float $timestamp): static
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTimestamp(): ?float
    {
        return $this->timestamp;
    }
}
