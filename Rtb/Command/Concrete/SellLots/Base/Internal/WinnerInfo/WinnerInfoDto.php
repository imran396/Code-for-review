<?php
/**
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 14, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base\Internal\WinnerInfo;

use Sam\Core\Service\CustomizableClass;

/**
 * Class WinnerInfoDto
 * @package Sam\Rtb
 */
class WinnerInfoDto extends CustomizableClass
{
    public readonly string $infoPublic;
    public readonly string $infoAdmin;
    public readonly string $name;
    public readonly string $username;
    /** @var string[] */
    public readonly array $ownerBidderNums;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $infoPublic
     * @param string $infoAdmin
     * @param string $status
     * @param string $username
     * @param string[] $ownerBidderNums
     * @return $this
     */
    public function construct(
        string $infoPublic,
        string $infoAdmin,
        string $status,
        string $username,
        array $ownerBidderNums
    ): static {
        $this->infoPublic = $infoPublic;
        $this->infoAdmin = $infoAdmin;
        $this->name = $status;
        $this->username = $username;
        $this->ownerBidderNums = $ownerBidderNums;
        return $this;
    }
}
