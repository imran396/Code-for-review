<?php
/**
 * Methods related to user registration in auction
 *
 * SAM-3904: Auction bidder registration class
 *
 * @author        Igors Kotlevskis
 * @since         Sep 14, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\AuctionBidder\Register\Single;

use Auction;
use AuctionBidder;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Load\AuctionBidderLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Config\ConfigAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Load\DataLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Log\LoggerAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Notify\NotifierAwareTrait;
use Sam\Bidder\AuctionBidder\Save\AuctionBidderDbLockerCreateTrait;
use Sam\Bidder\AuctionBidder\Save\AuctionBidderSaverCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Billing\AuctionRegistration\AuthAmountDetectorAwareTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\PaymentGateway\AuctionRegistration\AuthOrCapturePerformerCreateTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\AuctionBidder\AuctionBidderWriteRepositoryAwareTrait;
use Sam\User\Account\Save\UserAccountProducerAwareTrait;
use Sam\User\Info\UserInfoNoteManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Privilege\Validate\BidderPrivilegeCheckerAwareTrait;

/**
 * Class SingleUserRegistrator
 * @package Sam\Bidder\AuctionBidder\Register\Single
 */
class SingleUserRegistrator extends CustomizableClass
{
    use AuctionBidderDbLockerCreateTrait;
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderLoaderAwareTrait;
    use AuctionBidderSaverCreateTrait;
    use AuctionBidderWriteRepositoryAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuthAmountDetectorAwareTrait;
    use AuthOrCapturePerformerCreateTrait;
    use BidderNumPaddingAwareTrait;
    use BidderPrivilegeCheckerAwareTrait;
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigAwareTrait;
    use CreditCardValidatorAwareTrait;
    use DataLoaderAwareTrait;
    use LoggerAwareTrait;
    use NotifierAwareTrait;
    use SettingsManagerAwareTrait;
    use UserAccountProducerAwareTrait;
    use UserInfoNoteManagerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Registering user, e.g. buyer of agent.
     * Initial user can be found in $this->getConfig()->getUserId()
     * @var int
     */
    protected int $userId;
    protected Auction $auction;
    /** @var AuctionBidder[] */
    protected ?array $auctionBidders = null;
    protected ?bool $isAllUserRequireCcAuth = null;
    protected ?bool $isRegConfirmAutoApprove = null;
    protected ?bool $isOnAuctionRegistrationAuto = null;
    protected ?int $onAuctionRegistration = null;
    protected ?float $onAuctionRegistrationAmount = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(int $userId, int $auctionId): static
    {
        $this->userId = $userId;
        $this->auction = $this->getAuctionLoader()->load($auctionId);
        $this->getLogger()
            ->setUserId($userId)
            ->setAuctionId($auctionId);
        $this->getBidderPrivilegeChecker()->initByUserId($userId);
        return $this;
    }

    /**
     * Register single user in auctions of sale group without considering agent/buyers relations
     */
    public function register(): void
    {
        $this->logSettings();

        /**
         * Don't need to verify existence of Bidder Role, because it is checked for general source user in caller,
         * and list of his buyers is loaded with consideration of Bidder Role condition.
         */

        if (!$this->isAllowedRegistrationOfFlaggedUser()) {
            $message = "Flagged user is restricted from auction registration";
            $this->getConfig()->setErrorMessage($message);
            log_debug($this->getLogger()->decorate($message));
            return;
        }

        // AuctionBidder record is created and persisted anyway, or we take existing one
        $this->auctionBidders = $this->produceAuctionBidders();

        if ($this->getConfig()->isRegisterOnly()) {
            return;
        }

        if ($this->getConfig()->isAbsoluteApprove()) {
            $this->processAbsoluteApprove();
            return;
        }

        if ($this->isPreferredBidderAndAutoApproveAvailable()) {
            $this->processPreferredBidderAutoApprove();
            return;
        }

        if ($this->getConfig()->isOpayoThreeDSecureApproveEnabled()) {
            $this->processOpayoThreeDSecureApprove();
            return;
        }

        if ($this->isPlaceBidRequireCc()) {
            if ($this->isSimpleRegistrationRequired()) {
                $this->processNoCcCheckApprove();
                return;
            }

            if ($this->isAuthOrCaptureRequired()) {
                $this->processAuthOrCaptureApprove();
            }
        }
    }

    /**
     * Check, if we permit to register user according to constraint based on his flag
     * @return bool
     */
    protected function isAllowedRegistrationOfFlaggedUser(): bool
    {
        if (!$this->getConfig()->isRestrictionByUserFlag()) {
            // Restriction by user's flag is not required
            return true;
        }
        $isApprovableUser = $this->getDataLoader()->isApprovableUser($this->userId, $this->auction->Id);
        return $isApprovableUser;
    }

    /**
     * Check conditions, when user is preferred bidder
     * and enabled system option "Auto-assign bidder numbers for preferred bidders",
     *  or auto-assigning bidder# for preferred bidder was requested manually by "Yes" choice in respective dialog window,
     * and disabled system option "Require CC info for placing bids"
     * @return bool
     */
    protected function isPreferredBidderAndAutoApproveAvailable(): bool
    {
        $isPreferredBidder = $this->getBidderPrivilegeChecker()->hasPrivilegeForPreferred();
        $is = $isPreferredBidder
            && (
                $this->isRegConfirmAutoApprove()
                || $this->getConfig()->isAutoApprove()
            )
            && !$this->isAllUserRequireCcAuth();
        return $is;
    }

    /**
     * Check if registration without AuthOrCapture required,
     * when AuctionParameters->OnAuctionRegistration is disabled (NONE) or we should skip AuthOrCapture
     * @return bool
     */
    protected function isSimpleRegistrationRequired(): bool
    {
        $onAuctionRegistration = $this->getOnAuctionRegistration();
        $isAuthOrCapture = $this->getConfig()->isAuthOrCapture();
        $isRequired = !$onAuctionRegistration
            || !$isAuthOrCapture;
        if ($isRequired) {
            $logData = [
                "OnAuctionRegistration" => $onAuctionRegistration,
                "IsAuthOrCapture" => $isAuthOrCapture ? "On" : "Off",
            ];
            $message = "Simple registration required" . composeSuffix($logData);
            log_debug($this->getLogger()->decorate($message, $this->userId));
        }
        return $isRequired;
    }

    /**
     * Check if AuthOrCapture action required by AuctionParameters->OnAuctionRegistration option
     * @return bool
     */
    protected function isAuthOrCaptureRequired(): bool
    {
        $isAuthOrCapture = $this->getConfig()->isAuthOrCapture();
        $isRequired = $isAuthOrCapture
            && $this->getOnAuctionRegistration()  // is set to AUTH or CAPTURE
            && $this->getOnAuctionRegistrationAmount()
            && is_numeric($this->getOnAuctionRegistrationAmount())  // amount is not 0
            && $this->isOnAuctionRegistrationAuto() // 'execute' is set to 'automatically'
            && $this->getConfig()->getPostAuctionImportPremium() === null;
        if ($isRequired) {
            $logData = [
                'OnAuctionRegistration' => $this->getOnAuctionRegistration(),
                'OnAuctionRegistrationAmount' => $this->getOnAuctionRegistrationAmount(),
                'OnAuctionRegistrationAuto' => $this->isOnAuctionRegistrationAuto() ? 'On' : 'Off',
                'IsAuthOrCapture' => 'On',
            ];
            $message = 'Registration with AuthOrCapture required' . composeSuffix($logData);
            log_debug($this->getLogger()->decorate($message, $this->userId));
        }
        return $isRequired;
    }

    protected function processAbsoluteApprove(): void
    {
        $auctionBidders = $this->getAuctionBidders();
        foreach ($auctionBidders as $auctionBidder) {
            if ($this->canApproveAccordingToFlag($auctionBidder->UserId, $auctionBidder->AuctionId)) {
                $this->approveAndNotify($auctionBidder);
            }
        }
    }

    protected function processPreferredBidderAutoApprove(): void
    {
        /**
         * "Auto-assign bidder numbers for preferred bidders"
         * shouldn't matter when "Require CC info for placing bids" is active
         * it should always set auto-approve to true
         */
        if ($this->isPlaceBidRequireCc()) {
            $this->getConfig()->enableAutoApprove(true);
        }

        $auctionBidders = $this->getAuctionBidders();
        foreach ($auctionBidders as $auctionBidder) {
            $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
            $isAutoApprove = $auction
                && $this->detectAutoApprove($auctionBidder->UserId, $auction);
            if ($isAutoApprove) {
                $this->approveAndNotify($auctionBidder);
            } else {
                $message = "User cannot be approved. ";
                $message = $this->getLogger()->decorate($message, $this->userId, $auctionBidder->AuctionId);
                log_debug($message);
            }
        }
    }

    protected function processNoCcCheckApprove(): void
    {
        $isPreferredBidder = $this->getBidderPrivilegeChecker()->hasPrivilegeForPreferred();
        $auctionBidders = $this->getAuctionBidders();
        foreach ($auctionBidders as $auctionBidder) {
            $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
            if (!$auction) {
                log_error(
                    "Available auction not found"
                    . composeSuffix(["a" => $auctionBidder->AuctionId, "ab" => $auctionBidder->Id])
                );
                return;
            }
            $isAutoApprove = $this->detectAutoApprove($auctionBidder->UserId, $auction);
            if (
                $isAutoApprove
                && $isPreferredBidder
            ) {
                $this->approveAndNotify($auctionBidder);
            } else {
                $message = "User cannot be approved. ";
                if (!$isAutoApprove) {
                    $message .= "Auction auto approve disabled. ";
                }
                if (!$isPreferredBidder) {
                    $message .= "Bidder not preferred. ";
                }
                log_debug($this->getLogger()->decorate($message, $this->userId, $auctionBidder->AuctionId));
            }
        }
    }

    protected function processAuthOrCaptureApprove(): void
    {
        $auctionBidders = $this->getAuctionBidders();
        foreach ($auctionBidders as $auctionBidder) {
            $this->approveSingleUserWithAuthOrCapture($auctionBidder);
        }
    }

    protected function processOpayoThreeDSecureApprove(): void
    {
        $auctionBidders = $this->getAuctionBidders();
        foreach ($auctionBidders as $auctionBidder) {
            $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
            if (!$auction) {
                log_error(
                    "Available auction not found, when approving single user with auth or capture"
                    . composeSuffix(['a' => $auctionBidder->AuctionId, 'aub' => $auctionBidder->Id])
                );
                continue;
            }
            $authAmount = $this->getAuthAmountDetector()->detect($auction);
            $input = $this->getConfig()->getOpayoThreeDSecureApproveConfig();
            if ($input->hasSuccess) {
                $this->handleSuccessAuthOrCapture($auction, $auctionBidder, $authAmount);
            } else {
                $this->handleFailedAuthOrCapture($auction, $auctionBidder, $authAmount, $input->errorMessage);
            }
        }
    }

    /**
     * @param AuctionBidder $auctionBidder
     */
    public function approveSingleUserWithAuthOrCapture(AuctionBidder $auctionBidder): void
    {
        $auction = $this->getAuctionLoader()->load($auctionBidder->AuctionId, true);
        if (!$auction) {
            log_error(
                "Available auction not found, when approving single user with auth or capture"
                . composeSuffix(['a' => $auctionBidder->AuctionId, 'aub' => $auctionBidder->Id])
            );
            return;
        }
        $shouldDoAuthOrCapture = $this->createAuthOrCapturePerformer()->shouldDoAuthOrCapture(
            $auction->Id,
            $this->userId,
            $auction->AccountId
        );
        if ($shouldDoAuthOrCapture) {
            log_debug('Preparing for AuthOrCapture...');
            $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($this->userId);
            $errorMessage = '';
            $isCcTokenizationEnabled = $this->createBillingGateAvailabilityChecker()
                ->isCcProcessingAvailable($this->auction->AccountId);
            if (
                !$isCcTokenizationEnabled
                && !$this->isEwayEncryptionEnabled()
            ) {
                log_debug($this->getLogger()->decorate("CIM disabled"));
                if (!$userBilling->CcNumber) {
                    $errorMessage = "Missing credit card number";
                } else {
                    $ccNumber = $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber);
                    if (!$this->getCreditCardValidator()->validateNumber($ccNumber)) {
                        $errorMessage = "Invalid credit card number";
                    }
                }
            }

            $authAmount = $this->getAuthAmountDetector()->detect($auction);
            if ($errorMessage) {
                $this->setErrorMessage($errorMessage);
                $this->addUserNote($auction->Id);
                log_info(
                    $this->getLogger()->decorate(
                        "Authorization failed. {$errorMessage}",
                        $auctionBidder->UserId,
                        $auctionBidder->AuctionId
                    )
                );
                $this->getNotifier()->addAuthFailed($auctionBidder, ['amount' => $authAmount]);
                $this->disapproveFromSaleGroup();
            } else {
                $wasAuthorizationSuccessful = true;
                if ($authAmount) {
                    $results = $this->createAuthOrCapturePerformer()->doAuthOrCapture(
                        $this->getOnAuctionRegistration(),
                        $authAmount,
                        $this->userId,
                        $auction->Id,
                        $this->getConfig()->getCvv(),
                        $this->getConfig()->getEditorUserId(),
                        $this->getConfig()->getCarrierMethod()
                    );
                    $wasAuthorizationSuccessful = (bool)$results['success'];
                }

                if ($wasAuthorizationSuccessful) {
                    $this->handleSuccessAuthOrCapture($auction, $auctionBidder, $authAmount);
                } else {
                    $errorMessage = $results['errorMessage'] ?? '';
                    $this->handleFailedAuthOrCapture($auction, $auctionBidder, $authAmount, $errorMessage);
                }
            }
        } else {
            log_debug('no need to auth or capture');
            if ($this->detectAutoApprove($auctionBidder->UserId, $auction)) {
                $this->approveAndNotify($auctionBidder);
            }
        }
    }

    protected function handleSuccessAuthOrCapture(Auction $auction, AuctionBidder $auctionBidder, float $authAmount): void
    {
        log_debug($this->getLogger()->decorate('AuthOrCapture completed successfully'));

        $isAutoApprove = $this->detectAutoApprove($auctionBidder->UserId, $auction);
        if ($isAutoApprove) {
            $bidderNum = $this->suggestBidderNumberPad($auctionBidder);
            $auctionBidder = $this->getAuctionBidderHelper()->approve($auctionBidder, $bidderNum);
            $message = "Auction bidder approved with bidder# {$bidderNum}";
            log_debug($this->getLogger()->decorate($message, $this->userId, $auction->Id));
        } else {
            log_debug(
                $this->getLogger()->decorate(
                    'Auction auto approve disabled',
                    $this->userId,
                    $auction->Id
                )
            );
            // $auctionBidder = $this->getAuctionBidderHelper()->disapprove($auctionBidder);
        }

        $auctionBidder = $this->getAuctionBidderHelper()->addAuthInfo($auctionBidder, $authAmount);
        $auctionBidder = $this->createAuctionBidderSaver()
            ->construct()
            ->save($auctionBidder, $this->getConfig()->getEditorUserId());

        log_info(
            $this->getLogger()->decorate(
                "Authorization success",
                $auctionBidder->UserId,
                $auctionBidder->AuctionId
            )
        );
        $this->getNotifier()->addAuthSuccess($auctionBidder, ['amount' => $authAmount]);
    }

    protected function handleFailedAuthOrCapture(
        Auction $auction,
        AuctionBidder $auctionBidder,
        float $authAmount,
        string $errorMessage
    ): void {
        $this->disapproveAndDropAuthInfo($auctionBidder);
        $this->setErrorMessage($errorMessage);
        $this->addUserNote($auction->Id);
        log_info(
            $this->getLogger()->decorate(
                "Authorization failed. {$errorMessage}",
                $auctionBidder->UserId,
                $auctionBidder->AuctionId
            )
        );
        $this->getNotifier()->addAuthFailed($auctionBidder, ['amount' => $authAmount]);
    }

    /**
     * Determine auto-approving function availability, i.e. bidder# assignment.
     * @param int $userId
     * @param Auction $auction
     * @return bool
     */
    protected function detectAutoApprove(int $userId, Auction $auction): bool
    {
        $logData = ['u' => $userId, 'a' => $auction->Id];
        $isAutoApprove = $this->getConfig()->isAutoApprove();
        if (!$isAutoApprove) {
            log_debug("Auction bidder approving is not requested" . composeSuffix($logData));
            return false;
        }

        if ($auction->ManualBidderApprovalOnly) {
            log_debug("Only manual bidder approving is allowed for auction" . composeSuffix($logData));
            return false;
        }

        if (!$this->canApproveAccordingToFlag($userId, $auction->Id)) {
            log_debug("Rejected auction bidder approving of flagged user" . composeSuffix($logData));
            return false;
        }

        return true;
    }

    /**
     * Check if user can be approved in regard to his flag and service configuration.
     * @param int $userId
     * @param int $auctionId
     * @return bool
     */
    protected function canApproveAccordingToFlag(int $userId, int $auctionId): bool
    {
        if ($this->getConfig()->isFlaggedUserApprovable()) {
            // When option is enabled, then user can be approved independently of his flag
            return true;
        }

        $isApprovableUser = $this->getDataLoader()->isApprovableUser($userId, $auctionId);
        if (!$isApprovableUser) {
            // Reject approving of flagged user
            return false;
        }

        return true;
    }

    /**
     * Approve user in auction and notify user by email
     * @param AuctionBidder $auctionBidder
     */
    protected function approveAndNotify(AuctionBidder $auctionBidder): void
    {
        $bidderNumPad = $this->suggestBidderNumberPad($auctionBidder);
        $auctionBidder = $this->getAuctionBidderHelper()->approve($auctionBidder, $bidderNumPad);
        $message = "User approved in auction with bidder# {$bidderNumPad}. ";
        $auctionBidder = $this->createAuctionBidderSaver()
            ->construct()
            ->save($auctionBidder, $this->getConfig()->getEditorUserId());
        log_debug($this->getLogger()->decorate($message, $auctionBidder->UserId, $auctionBidder->AuctionId));
        $this->getNotifier()->addAuctionApproved($auctionBidder);
    }

    /**
     * Produce AuctionBidder records for passed user in auctions of the same sale group
     * @return AuctionBidder[]
     */
    private function produceAuctionBidders(): array
    {
        $auctionBidders = [];
        $auctionRows = $this->getDataLoader()->getRegisteringAuctionRows(
            $this->auction,
            $this->getConfig()->shouldRegisterInSaleGroup()
        );
        foreach ($auctionRows as $row) {
            [$auctionId, $accountId] = array_values($row);
            $auctionId = (int)$auctionId;
            $accountId = (int)$accountId;
            $this->createAuctionBidderDbLocker()->lock($this->userId, $auctionId);
            $auctionBidder = $this->getAuctionBidderLoader()
                ->enableEntityMemoryCacheManager(false)
                ->load($this->userId, $auctionId);
            if (!$auctionBidder) {
                $auctionBidder = $this->getAuctionBidderHelper()->create($this->userId, $auctionId);
                $auctionBidder->CarrierMethod = $this->getConfig()->getCarrierMethod();
                if ($this->getConfig()->isPostAuctionImport()) {
                    $auctionBidder->PostAucImportPremium = $this->getConfig()->getPostAuctionImportPremium();
                }
                $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $this->getConfig()->getEditorUserId());
                log_debug($this->getLogger()->decorate("User registered in auction", $this->userId, $auctionId));
                // Sale Group auctions are from same account
                $this->getUserAccountProducer()->add($this->userId, $accountId, $this->getConfig()->getEditorUserId());
                $this->getNotifier()->addAuctionRegistered($auctionBidder);
                $auctionBidders[$auctionBidder->AuctionId] = $auctionBidder;
            } elseif ($this->getAuctionBidderHelper()->isApproved($auctionBidder)) {
                // Skip already approved bidders, but update PostAucImportPremium
                if ($this->getConfig()->isPostAuctionImport()) {
                    // But we need to update premium
                    $auctionBidder->PostAucImportPremium = $this->getConfig()->getPostAuctionImportPremium();
                    $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $this->getConfig()->getEditorUserId());
                }
            } else {
                if ($this->getConfig()->isPostAuctionImport()) {
                    $auctionBidder->PostAucImportPremium = $this->getConfig()->getPostAuctionImportPremium();
                }
                $auctionBidders[$auctionBidder->AuctionId] = $auctionBidder;
            }
            $this->createAuctionBidderDbLocker()->release($this->userId, $auctionId);
        }

        ksort($auctionBidders);

        return $auctionBidders;
    }

    protected function disapproveFromSaleGroup(): void
    {
        $auctionBidders = $this->getAuctionBidders();
        foreach ($auctionBidders as $auctionBidder) {
            $this->disapproveAndDropAuthInfo($auctionBidder);
        }
    }

    /**
     * @param AuctionBidder $auctionBidder
     */
    protected function disapproveAndDropAuthInfo(AuctionBidder $auctionBidder): void
    {
        $auctionBidder = $this->getAuctionBidderHelper()->disapprove($auctionBidder);
        $auctionBidder = $this->getAuctionBidderHelper()->dropAuthInfo($auctionBidder);
        $this->getAuctionBidderWriteRepository()->saveWithModifier($auctionBidder, $this->getConfig()->getEditorUserId());
    }

    /**
     * Add user note about failed AuthOrCapture operation.
     * It adds CC info, when $userBilling passed.
     * @param int $auctionId
     */
    protected function addUserNote(int $auctionId): void
    {
        $userInfo = $this->getUserLoader()->loadUserInfoOrCreate($this->userId);
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($this->userId);
        $errorMessage = $this->getConfig()->getErrorMessage();
        $this->getUserInfoNoteManager()->applyAuctionRegistration(
            $userInfo,
            $errorMessage,
            $auctionId,
            $userBilling,
            $this->getConfig()->getEditorUserId()
        );
    }

    /**
     * @param string $message
     */
    protected function setErrorMessage(string $message): void
    {
        $this->getConfig()->setErrorMessage($message);
    }

    protected function logSettings(): void
    {
        $config = $this->getConfig();
        $isApprovableUser = $this->getDataLoader()->isApprovableUser($this->userId, $this->auction->AccountId);
        $messages = [
            "Absolute approve" => $this->boolToString($config->isAbsoluteApprove()),
            "Restriction by user flag" => $this->boolToString($config->isRestrictionByUserFlag()),
            "Flagged user approvable" => $this->boolToString($config->isFlaggedUserApprovable()),
            "Preferred bidder" => $this->boolToString($this->getBidderPrivilegeChecker()->hasPrivilegeForPreferred()),
            "RegConfirmAutoApprove" => $this->boolToString($this->isRegConfirmAutoApprove()),
            "Require CC auth for all" => $this->boolToString($this->isAllUserRequireCcAuth()),
            "Auto approve" => $this->boolToString($config->isAutoApprove()),
            "Approvable user" => $this->boolToString($isApprovableUser),
        ];

        $generalUserId = $config->getUserId();

        $hasGeneralUserPrivilegeForAgent = $this->getBidderPrivilegeChecker()
            ->initByUserId($generalUserId)
            ->hasPrivilegeForAgent();
        if ($hasGeneralUserPrivilegeForAgent) {
            $messages["Should register agent's buyers"] = $this->boolToString($config->shouldRegisterBuyers());
            $messages["Current user"] = $generalUserId === $this->userId ? 'Agent' : 'Buyer';
        }

        $isSaleGroup = (bool)$this->auction->SaleGroup;
        if ($isSaleGroup) {
            $messages["Should register in sale group"] = $this->boolToString($config->shouldRegisterInSaleGroup());
            $messages["Is sale group"] = $this->boolToString((bool)$this->auction->SaleGroup);
        }

        if (!$this->isPreferredBidderAndAutoApproveAvailable()) {
            $messages["Place Bid Require CC"] = $this->boolToString($this->isPlaceBidRequireCc());
            $messages["On auction registration"] = $this->getOnAuctionRegistration();
        }

        $message = "Single user registration options" . composeSuffix($messages);
        log_debug($this->getLogger()->decorate($message, $this->userId));
    }

    /**
     * Return "On" or "Off" for boolean value.
     * @param bool $value
     * @return string
     */
    protected function boolToString(bool $value): string
    {
        return $value ? "On" : "Off";
    }

    /**
     * @return AuctionBidder[]
     */
    protected function getAuctionBidders(): array
    {
        if ($this->auctionBidders === null) {
            $this->auctionBidders = $this->produceAuctionBidders();
        }
        return $this->auctionBidders;
    }

    /**
     * @return bool
     */
    protected function isPlaceBidRequireCc(): bool
    {
        return (bool)$this->getSettingsManager()
            ->get(Constants\Setting::PLACE_BID_REQUIRE_CC, $this->auction->AccountId);
    }

    /**
     * @return bool
     */
    protected function isEwayEncryptionEnabled(): bool
    {
        $enabled = false;
        $isEwayEnabled = (bool)$this->getSettingsManager()->get(Constants\Setting::CC_PAYMENT_EWAY, $this->auction->AccountId);
        $isEwayEncryptionEnabled = $this->getSettingsManager()->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $this->auction->AccountId);
        if (
            $isEwayEnabled
            && $isEwayEncryptionEnabled
        ) {
            $enabled = true;
        }
        return $enabled;
    }

    /**
     * @return bool
     */
    protected function isAllUserRequireCcAuth(): bool
    {
        if ($this->isAllUserRequireCcAuth === null) {
            $this->isAllUserRequireCcAuth = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::ALL_USER_REQUIRE_CC_AUTH, $this->auction->AccountId);
        }
        return $this->isAllUserRequireCcAuth;
    }

    /**
     * @return bool
     */
    public function isRegConfirmAutoApprove(): bool
    {
        if ($this->isRegConfirmAutoApprove === null) {
            $this->isRegConfirmAutoApprove = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::REG_CONFIRM_AUTO_APPROVE, $this->auction->AccountId);
        }
        return $this->isRegConfirmAutoApprove;
    }

    /**
     * @return bool
     */
    public function isOnAuctionRegistrationAuto(): bool
    {
        if ($this->isOnAuctionRegistrationAuto === null) {
            $this->isOnAuctionRegistrationAuto = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::ON_AUCTION_REGISTRATION_AUTO, $this->auction->AccountId);
        }
        return $this->isOnAuctionRegistrationAuto;
    }

    /**
     * @return int
     */
    public function getOnAuctionRegistration(): int
    {
        if ($this->onAuctionRegistration === null) {
            $this->onAuctionRegistration = (int)$this->getSettingsManager()
                ->get(Constants\Setting::ON_AUCTION_REGISTRATION, $this->auction->AccountId);
        }
        return $this->onAuctionRegistration;
    }

    /**
     * @return float
     */
    public function getOnAuctionRegistrationAmount(): float
    {
        if ($this->onAuctionRegistrationAmount === null) {
            $this->onAuctionRegistrationAmount = (float)$this->getSettingsManager()
                ->get(Constants\Setting::ON_AUCTION_REGISTRATION_AMOUNT, $this->auction->AccountId);
        }
        return $this->onAuctionRegistrationAmount;
    }

    /**
     * Search for next available bidder#, or return predefined one for general user.
     * @param AuctionBidder $auctionBidder
     * @return string padded bidder#
     */
    protected function suggestBidderNumberPad(AuctionBidder $auctionBidder): string
    {
        $generalUserId = $this->getConfig()->getUserId();
        $bidderNum = $this->getConfig()->getBidderNumber();
        if (
            $auctionBidder->UserId === $generalUserId
            && !$this->getBidderNumberPadding()->isNone($bidderNum)
        ) {
            return $this->getBidderNumberPadding()->add($bidderNum);
        }

        return (string)$this->getAuctionBidderHelper()->suggestBidderNum($auctionBidder);
    }
}
