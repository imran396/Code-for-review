<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           9/24/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Auction\Validate;

use Sam\Application\Controller\Responsive\Auction\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Application\Controller\Responsive\Auction\Validate\AuctionControllerValidationResult as Result;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;

/**
 * Class AuctionControllerValidator
 * @package Sam\Application\Controller\Responsive\Auction
 */
class AuctionControllerValidator extends CustomizableClass
{
    use DataProviderCreateTrait;
    use OptionalsTrait;

    // --- Input values ---

    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LIVE_HYBRID_ACTIONS = 'liveHybridActions'; // string[]

    /** @var string[] */
    private const LIVE_HYBRID_ACTIONS_DEF = [
        Constants\ResponsiveRoute::AA_ABSENTEE_BIDS,
        Constants\ResponsiveRoute::AA_LIVE_SALE,
    ];

    // --- Constructors ---

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array|null $optionals
     * @return $this
     */
    public function construct(?array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    // --- Main method ---

    /**
     * Validate/Check if Auction ID exists, and not archived or deleted
     * @param int|null $auctionId null leads to incorrect auctionId error
     * @param string $actionName
     * @return Result
     */
    public function validate(?int $auctionId, string $actionName): Result
    {
        $result = Result::new()->construct();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);
        $dataProvider = $this->createDataProvider();

        $auction = $dataProvider->loadAuction($auctionId, $isReadOnlyDb);
        if (!$auction) {
            return $result->addError(Result::ERR_INCORRECT_AUCTION_ID);
        }

        if ($auction->isDeletedOrArchived()) {
            return $result->addError(Result::ERR_UNAVAILABLE_AUCTION);
        }

        if (empty($actionName)) {
            return $result->addError(Result::ERR_INCORRECT_ACTION);
        }

        // Some actions are available for concrete auction types only (SAM-3311)
        if (
            $this->isActionExpected($actionName)
            && !$auction->isLiveOrHybrid()
        ) {
            return $result->addError(Result::ERR_ACTION_TO_AUCTION_TYPE);
        }

        if (!$dataProvider->isAllowedForAuction($auction)) {
            return $result->addError(Result::ERR_DOMAIN_AUCTION_VISIBILITY);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    // --- Internal logic ---

    /**
     * @param string $actionName
     * @return bool
     */
    protected function isActionExpected(string $actionName): bool
    {
        $liveHybridActions = (array)$this->fetchOptional(self::OP_LIVE_HYBRID_ACTIONS);
        return in_array($actionName, $liveHybridActions, true);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;
        $optionals[self::OP_LIVE_HYBRID_ACTIONS] = $optionals[self::OP_LIVE_HYBRID_ACTIONS]
            ?? self::LIVE_HYBRID_ACTIONS_DEF;
        $this->setOptionals($optionals);
    }
}
