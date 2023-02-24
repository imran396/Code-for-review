<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/01/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Auction\Validate\Registration;

use Sam\Application\Controller\Responsive\Auction\Validate\Registration\AuctionRegistrationControllerValidationResult as Result;
use Sam\Application\Controller\Responsive\Auction\Validate\Registration\Internal\Load\DataProviderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionRegistrationControllerValidator
 * @package Sam\Application\Controller\Responsive\Auction
 */
class AuctionRegistrationControllerValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * These actions require from user to be NOT registered in auction yet.
     * @var string[]
     */
    private array $shouldNotBeRegisteredInAuctionActions = [
        Constants\ResponsiveRoute::AR_CONFIRM_BIDDER_OPTIONS,
        Constants\ResponsiveRoute::AR_CONFIRM_SHIPPING,
        Constants\ResponsiveRoute::AR_SAVE_CONFIRM_SHIPPING,
    ];

    private const AVAILABLE_ACTIONS_DEFAULT = Constants\ResponsiveRoute::AR_ACTIONS;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate/Check if Auction ID exists, and not archived or deleted
     * @param int|null $auctionId
     * @param string $actionName
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     * @return AuctionRegistrationControllerValidationResult
     */
    public function validate(
        ?int $auctionId,
        string $actionName,
        ?int $editorUserId,
        bool $isReadOnlyDb = false
    ): AuctionRegistrationControllerValidationResult {
        $dataProvider = $this->createDataProvider();
        $result = Result::new()->construct();

        if (!$editorUserId) {
            return $result->addError(Result::ERR_ANONYMOUS_USER);
        }

        $auction = $dataProvider->loadAuction($auctionId, $isReadOnlyDb);
        if (!$auction) {
            return $result->addError(Result::ERR_INCORRECT_AUCTION_ID);
        }

        if (!$auction->isAvailableAuctionStatus()) {
            return $result->addError(Result::ERR_UNAVAILABLE_AUCTION);
        }

        if ($auction->isClosed()) {
            return $result->addError(Result::ERR_AUCTION_CLOSED);
        }

        $isAuctionAccountFound = $dataProvider->existAuctionAccount($auction->AccountId, $isReadOnlyDb);
        if (!$isAuctionAccountFound) {
            return $result->addError(Result::ERR_AUCTION_ACCOUNT_NOT_FOUND);
        }

        if (!in_array($actionName, self::AVAILABLE_ACTIONS_DEFAULT, true)) {
            return $result->addError(Result::ERR_INCORRECT_ACTION);
        }

        $isAllowed = $dataProvider->isAllowedForAuction($auction);
        if (!$isAllowed) {
            return $result->addError(Result::ERR_DOMAIN_AUCTION_VISIBILITY);
        }

        if (!$this->isEmailVerifiedForAuthorizedUser(true)) {
            return $result->addError(Result::ERR_UNVERIFIED_EMAIL_FOR_AUTH_USER);
        }

        if (!$dataProvider->hasBidderRole($editorUserId)) {
            return $result->addError(Result::ERR_EDITOR_USER_MUST_BE_BIDDER);
        }

        $inShouldNotBeRegisteredActions = in_array($actionName, $this->shouldNotBeRegisteredInAuctionActions, true);
        if (
            $inShouldNotBeRegisteredActions
            && $dataProvider->isAuctionRegistered($editorUserId, $auctionId)
        ) {
            return $result->addError(Result::ERR_ALREADY_REGISTERED);
        }

        if (!$dataProvider->detectIfRegistrationActiveByDatesRange($auction)) {
            return $result->addError(Result::ERR_REGISTRATION_NOT_STARTED);
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }

    /**
     * Check authorized user email verified.
     * @param bool $isReadOnlyDb
     * @return bool
     */
    private function isEmailVerifiedForAuthorizedUser(bool $isReadOnlyDb = false): bool
    {
        $isVerifyEmail = $this->createDataProvider()->isAppVerifyEmailEnabledForMainAccount();
        if (!$isVerifyEmail) {
            return true;
        }

        $userAuthentication = $this->createDataProvider()->getEditorUserAuthenticationOrCreate($isReadOnlyDb);
        return $userAuthentication->EmailVerified;
    }

}
