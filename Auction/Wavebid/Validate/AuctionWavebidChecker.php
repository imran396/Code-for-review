<?php
/**
 * SAM-6872: Extract Wavebid business logic from forms
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Wavebid\Validate;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Wavebid\Validate\WavebidStatusPureChecker;
use Sam\Core\Constants;
use Sam\Settings\SettingsManager;

/**
 * Class AuctionWavebidChecker
 * @package Sam\Auction\Wavebid\Validate
 */
class AuctionWavebidChecker extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_WAVEBID_ENDPOINT = 'wavebidEndpoint'; // string
    public const OP_WAVEBID_UAT = 'wavebidUat'; // string

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    public function isAvailableForAuction(Auction $auction): bool
    {
        return $this->isAvailable($auction->WavebidAuctionGuid, $auction->AccountId);
    }

    /**
     * @param string $wavebidAuctionGuid
     * @param int $accountId
     * @return bool
     */
    public function isAvailable(string $wavebidAuctionGuid, int $accountId): bool
    {
        return WavebidStatusPureChecker::new()->construct()
            ->isAvailable(
                $wavebidAuctionGuid,
                $this->fetchOptional(self::OP_WAVEBID_ENDPOINT, [$accountId]),
                $this->fetchOptional(self::OP_WAVEBID_UAT, [$accountId])
            );
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_WAVEBID_ENDPOINT] = $optionals[self::OP_WAVEBID_ENDPOINT]
            ?? static function (int $accountId): string {
                return (string)SettingsManager::new()
                    ->get(Constants\Setting::WAVEBID_ENDPOINT, $accountId);
            };

        $optionals[self::OP_WAVEBID_UAT] = $optionals[self::OP_WAVEBID_UAT]
            ?? static function (int $accountId): string {
                return (string)SettingsManager::new()
                    ->get(Constants\Setting::WAVEBID_UAT, $accountId);
            };

        $this->setOptionals($optionals);
    }
}
