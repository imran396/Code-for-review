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

namespace Sam\Application\Controller\Admin\Auction\Validate;

use Auction;
use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;
use Sam\Application\Controller\Admin\Auction\Validate\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Application\Controller\Admin\Auction\Validate\AuctionControllerValidationResult as Result;
use Sam\Application\Controller\Admin\Auction\Validate\Internal\Input\AuctionControllerValidationInput as Input;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class AuctionControllerValidator
 * @package Sam\Application\Controller\Admin\Auction
 */
class AuctionControllerValidator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use OptionalsTrait;

    // --- Input values ---
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LIVE_ACTIONS = 'liveActions'; // string[]
    public const OP_LIVE_HYBRID_ACTIONS = 'liveHybridActions'; // string[]
    public const OP_LOT_ACTIONS = 'lotActions'; // string[]
    public const OP_RUN_ACTIONS = 'runActions'; // string[]

    // --- Internal values ---

    /**
     * Allowed actions for Live/Hybrid auction
     * @var array<string, string[]>
     */
    private const LIVE_HYBRID_ACTIONS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_BIDDERS_ABSENTEE,
            Constants\AdminRoute::AMA_BIDDERS_ABSENTEE_LOT,
            Constants\AdminRoute::AMA_LOT_PRESALE,
            Constants\AdminRoute::AMA_LIVE_TRAIL,
            Constants\AdminRoute::AMA_PRESALE_CSV,
            Constants\AdminRoute::AMA_PROJECTOR,
            Constants\AdminRoute::AMA_PROJECTOR_POP,
            Constants\AdminRoute::AMA_PROJECTOR_POP_SIMPLE,
            Constants\AdminRoute::AMA_RUN,
            Constants\AdminRoute::AMA_PHONE_BIDDERS,
        ],
    ];

    /**
     * Allowed actions for Live auction
     * @var array<string, string[]>
     */
    private const LIVE_ACTIONS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_AUCTIONEER
        ]
    ];

    /**
     * Allowed lot related actions
     * @var array<string, string[]>
     */
    private const LOT_ACTIONS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_ADJOINING_LOT,
            Constants\AdminRoute::AMA_LOT_BID_HISTORY,
            Constants\AdminRoute::AMA_EDIT_LOT,
            Constants\AdminRoute::AMA_LOT_PRESALE,
            Constants\AdminRoute::AMA_REMOVE_LOT,
            Constants\AdminRoute::AMA_RESET_ALL_VIEWS,
        ],
        Constants\AdminRoute::C_MANAGE_PLACE_BID => [
            Constants\AdminRoute::AMPB_INDEX,
        ],
    ];

    /**
     * Run live specific actions
     * @var array<string, string[]>
     */
    private const RUN_ACTIONS = [
        Constants\AdminRoute::C_MANAGE_AUCTIONS => [
            Constants\AdminRoute::AMA_RUN,
        ]
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

    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Validate/Check if Auction ID exists, and not archived or deleted
     *
     * @param Input $input
     *
     * @return Result
     */
    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();
        $isReadOnlyDb = (bool)$this->fetchOptional(self::OP_IS_READ_ONLY_DB);

        $auction = $dataProvider->loadAuction($input->auctionId, $isReadOnlyDb);
        if (!$auction) {
            return $result->addError(Result::ERR_INCORRECT_AUCTION_ID);
        }

        if (!$dataProvider->isAuctionAccountAvailable($auction->AccountId, $isReadOnlyDb)) {
            return $result->addError(Result::ERR_AUCTION_ACCOUNT_NOT_FOUND);
        }

        if (
            $auction->AccountId !== $input->systemAccountId
            && !$this->isMainSystemAccount($input->systemAccountId)
        ) {
            return $result->addError(Result::ERR_AUCTION_AND_PORTAL_ACCOUNTS_NOT_MATCH);
        }

        $availableAuctionTypes = $dataProvider->getAvailableAuctionTypes($input->systemAccountId);
        if (!$this->isAuctionTypeAvailable($auction->AuctionType, $availableAuctionTypes)) {
            return $result->addError(Result::ERR_AUCTION_TYPE_NOT_AVAILABLE);
        }

        if (!$input->actionName) {
            return $result->addError(Result::ERR_EMPTY_ACTION);
        }

        if (!$input->controllerName) {
            return $result->addError(Result::ERR_EMPTY_CONTROLLER);
        }

        if (!$this->isControllerKnown($input->controllerName)) {
            return $result->addError(Result::ERR_INCORRECT_CONTROLLER);
        }

        // Some actions are available for concrete auction types only (SAM-3311)
        if (!$this->isActionExpected($auction, $input->actionName, $input->controllerName)) {
            return $result->addError(Result::ERR_ACTION_TO_AUCTION_TYPE);
        }

        if (!$this->isAuctionStatusAllowed($auction, $input->actionName, $input->controllerName)) {
            return $result->addError(Result::ERR_UNAVAILABLE_AUCTION);
        }

        if (!$this->isAuctionLotAllowed($input->lotItemId, $input->auctionId, $input->actionName, $input->controllerName)) {
            return $result->addError(Result::ERR_UNAVAILABLE_AUCTION_LOT);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    /**
     * Check auction's type availability
     * @param string $auctionType
     * @param string[] $availableTypes
     * @return bool
     */
    protected function isAuctionTypeAvailable(string $auctionType, array $availableTypes): bool
    {
        return in_array($auctionType, $availableTypes, true);
    }

    /**
     * @param int|null $systemAccountId
     * @return bool
     */
    protected function isMainSystemAccount(?int $systemAccountId): bool
    {
        $isMain = MultipleTenantAccountSimpleChecker::new()->isMainAccount(
            $systemAccountId,
            $this->cfg()->get('core->portal->enabled'),
            $this->cfg()->get('core->portal->mainAccountId'),
        );
        return $isMain;
    }

    /**
     * Simple check, that controller name is known
     * @param string $controllerName
     * @return bool
     */
    protected function isControllerKnown(string $controllerName): bool
    {
        $liveHybridActions = $this->fetchOptional(self::OP_LIVE_HYBRID_ACTIONS);
        $liveActions = $this->fetchOptional(self::OP_LIVE_ACTIONS);
        $lotActions = $this->fetchOptional(self::OP_LOT_ACTIONS);
        return array_key_exists($controllerName, $liveHybridActions)
            || array_key_exists($controllerName, $liveActions)
            || array_key_exists($controllerName, $lotActions);
    }

    /**
     * Check, if action is expected according to auction type
     * @param Auction $auction
     * @param string $actionName
     * @param string $controllerName
     * @return bool
     */
    protected function isActionExpected(Auction $auction, string $actionName, string $controllerName): bool
    {
        $liveHybridActions = $this->fetchOptional(self::OP_LIVE_HYBRID_ACTIONS);
        $liveHybridActionsForController = $liveHybridActions[$controllerName] ?? [];
        if (in_array($actionName, $liveHybridActionsForController, true)) {
            return $auction->isLiveOrHybrid();
        }

        $liveActions = $this->fetchOptional(self::OP_LIVE_ACTIONS);
        $liveActionsForController = $liveActions[$controllerName] ?? [];
        if (in_array($actionName, $liveActionsForController, true)) {
            return $auction->isLive();
        }

        return true;
    }

    /**
     * Check page availability according to auction status
     * @param Auction $auction
     * @param string $actionName
     * @param string $controllerName
     * @return bool
     */
    protected function isAuctionStatusAllowed(Auction $auction, string $actionName, string $controllerName): bool
    {
        $runActions = $this->fetchOptional(self::OP_RUN_ACTIONS);
        $runActionsForController = $runActions[$controllerName] ?? [];
        // For "run" action we don't allow Archived auctions
        $availableStatuses = in_array($actionName, $runActionsForController, true)
            ? Constants\Auction::$availableAuctionStatuses
            : Constants\Auction::$notDeletedAuctionStatuses;
        return in_array($auction->AuctionStatusId, $availableStatuses, true);
    }

    /**
     * Check auction lot related page case
     *
     * @param int|null $lotItemId
     * @param int $auctionId
     * @param string $actionName
     * @param string $controllerName
     * @return bool
     */
    protected function isAuctionLotAllowed(
        ?int $lotItemId,
        int $auctionId,
        string $actionName,
        string $controllerName
    ): bool {
        $lotActions = $this->fetchOptional(self::OP_LOT_ACTIONS);
        $lotActionsForController = $lotActions[$controllerName] ?? [];
        // Check lot id if action require it:
        if (in_array($actionName, $lotActionsForController, true)) {
            // check that lot present in this auction:
            $isAuctionLot = false;
            if ($lotItemId) {
                $isReadOnlyDb = $this->fetchOptional(self::OP_IS_READ_ONLY_DB);
                $isAuctionLot = $this->createDataProvider()->isAuctionLotAvailable($lotItemId, $auctionId, $isReadOnlyDb);
            }
            return $lotItemId && $isAuctionLot;
        }
        return true;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_IS_READ_ONLY_DB] = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;

        $optionals[self::OP_LIVE_HYBRID_ACTIONS] = $optionals[self::OP_LIVE_HYBRID_ACTIONS]
            ?? self::LIVE_HYBRID_ACTIONS;

        $optionals[self::OP_LIVE_ACTIONS] = $optionals[self::OP_LIVE_ACTIONS]
            ?? self::LIVE_ACTIONS;

        $optionals[self::OP_LOT_ACTIONS] = $optionals[self::OP_LOT_ACTIONS]
            ?? self::LOT_ACTIONS;

        $optionals[self::OP_RUN_ACTIONS] = $optionals[self::OP_RUN_ACTIONS]
            ?? self::RUN_ACTIONS;

        $this->setOptionals($optionals);
    }
}
