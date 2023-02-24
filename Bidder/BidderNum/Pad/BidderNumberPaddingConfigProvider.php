<?php
/**
 * Bidder# padding related functions
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 * SAM-9648: Drop "approved" flag from "auction_bidder" table
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\BidderNum\Pad;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Provide padding char and padding length config options
 *
 * Class BidderNumberPaddingConfigProvider
 * @package Sam\Bidder\BidderNum\Pad
 */
class BidderNumberPaddingConfigProvider extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getChar(): string
    {
        $char = $this->cfg()->get('core->user->bidderNumber->padString');
        if (!in_array($char, Constants\Bidder::PADDING_CHARACTERS, true)) {
            $defaultChar = Constants\Bidder::PCH_DEFAULT;
            log_error("Unexpected padding character \"{$char}\" corrected to default one \"{$defaultChar}\"");
            return $defaultChar;
        }
        return $char;
    }

    public function getLength(): int
    {
        return (int)$this->cfg()->get('core->user->bidderNumber->padLength');
    }
}
