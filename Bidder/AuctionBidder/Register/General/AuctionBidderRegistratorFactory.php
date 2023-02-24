<?php
/**
 * Public facade API for user registration in auction.
 * Its methods describe business needs.
 *
 * SAM-3904: Auction bidder registration class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidder\AuctionBidder\Register\General;

use Sam\Bidder\AuctionBidder\Register\Config\Config;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Bidder\AuctionBidder\Register\Config\Opayo\OpayoThreeDSecureAuctionBidderRegistrationConfig;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionBidderRegistrationFacade
 * @package Sam\Bidder\AuctionBidder\Register\General
 */
class AuctionBidderRegistratorFactory extends CustomizableClass
{
    use AuctionBidderRegistratorCreateTrait;
    use BidderNumPaddingAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * For web registration at public responsive site
     * @param int $userId
     * @param int $auctionId
     * @param string $carrierMethod
     * @param int $editorUserId
     * @param string $cvv
     * @return AuctionBidderRegistrator
     */
    public function createWebPublicRegistrator(
        int $userId,
        int $auctionId,
        string $carrierMethod,
        int $editorUserId,
        string $cvv
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->enableAuthOrCapture(true)
            ->enableAutoApprove(true)
            ->enableEmailNotification(true)
            ->enableRegisterBuyers(true)
            ->enableRegisterInSaleGroup(true)
            ->enableRestrictionByBidderRole(true)
            ->enableRestrictionByUserFlag(true)
            ->setAuctionId($auctionId)
            ->setCarrierMethod($carrierMethod)
            ->setEditorUserId($editorUserId)
            ->setUserId($userId)
            ->setCvv($cvv);
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }

    /**
     * For Opayo billing API registration
     * @param int $userId
     * @param int $auctionId
     * @param int $editorUserId
     * @param string $carrierMethod
     * @param OpayoThreeDSecureAuctionBidderRegistrationConfig $opayoAuctionRegistrationConfig
     * @return AuctionBidderRegistrator
     */
    public function createBillingApiOpayoRegistrator(
        int $userId,
        int $auctionId,
        int $editorUserId,
        string $carrierMethod,
        OpayoThreeDSecureAuctionBidderRegistrationConfig $opayoAuctionRegistrationConfig
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->setCarrierMethod($carrierMethod)
            ->enableOpayoThreeDSecureApprove($opayoAuctionRegistrationConfig)
            ->enableAutoApprove(true)
            ->enableEmailNotification(true)
            ->enableRegisterBuyers(true)
            ->enableRegisterInSaleGroup(true)
            ->enableRestrictionByBidderRole(true)
            ->enableRestrictionByUserFlag(true)
            ->setAuctionId($auctionId)
            ->setEditorUserId($editorUserId)
            ->setUserId($userId);
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }

    /**
     * For web registration at admin Auction Bidders page
     * @param int $userId
     * @param int $auctionId
     * @param bool $isAutoApprovePreferred
     * @param int $editorUserId
     * @return AuctionBidderRegistrator
     */
    public function createWebAdminRegistrator(
        int $userId,
        int $auctionId,
        bool $isAutoApprovePreferred,
        int $editorUserId
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->enableAutoApprove($isAutoApprovePreferred)
            ->enableEmailNotification(true)
            ->enableRegisterBuyers(true)
            ->enableRegisterInSaleGroup(true)
            ->setAuctionId($auctionId)
            ->setEditorUserId($editorUserId)
            ->setUserId($userId);
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }

    /**
     * For registering floor bidder at clerk console of admin site.
     * @param int $userId
     * @param int $auctionId
     * @param string $bidderNumber
     * @param int $editorUserId
     * @return AuctionBidderRegistrator
     */
    public function createWebAdminFloorBidderRegistrator(
        int $userId,
        int $auctionId,
        string $bidderNumber,
        int $editorUserId
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->assignBidderNumber($bidderNumber)
            ->enableAbsoluteApprove(true)
            ->setAuctionId($auctionId)
            ->setEditorUserId($editorUserId)
            ->setUserId($userId);
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }

    /**
     * For Post Auction CSV Import
     * @param int $userId
     * @param int $auctionId
     * @param float $premium
     * @param int $editorUserId
     * @return AuctionBidderRegistrator
     */
    public function createCsvPostAuctionImport(
        int $userId,
        int $auctionId,
        float $premium,
        int $editorUserId
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->enableAbsoluteApprove(true)
            ->enableEmailNotification(true)
            ->enablePostAuctionImport(true)
            ->enableRegisterBuyers(true)
            ->enableRegisterInSaleGroup(true)
            ->setAuctionId($auctionId)
            ->setEditorUserId($editorUserId)
            ->setPostAuctionImportPremium($premium)
            ->setUserId($userId);
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }

    /**
     * For Auction Bidder CSV import
     */
    public function createCsvAuctionBidderImport(
        int $userId,
        int $auctionId,
        string $bidderNumber,
        bool $isAbsoluteApprove,
        bool $isEmailNotification,
        int $editorUserId
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->assignBidderNumber($bidderNumber)
            ->enableRegisterInSaleGroup(true)
            ->enableEmailNotification($isEmailNotification)
            ->setAuctionId($auctionId)
            ->setEditorUserId($editorUserId)
            ->setUserId($userId);
        if (
            $isAbsoluteApprove
            || $this->getBidderNumberPadding()->isFilled($bidderNumber)
        ) {
            $config->enableAbsoluteApprove(true);
            return $this->createAuctionBidderRegistrator()->setConfig($config);
        }
        $config->enableRegisterOnly(true);
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }

    /**
     * TODO
     * @param int $userId
     * @param int $auctionId
     * @param int $editorUserId
     * @return AuctionBidderRegistrator
     */
    public function createWebBidMoveRegistrator(
        int $userId,
        int $auctionId,
        int $editorUserId
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->setAuctionId($auctionId)
            ->setEditorUserId($editorUserId)
            ->setUserId($userId)
            // ->enableAbsoluteApprove()
            // ->enableApproveOnlyPreferredWhenNoCcCheck(false)
            // ->enableManualApprove()
            // ->enableRegisterBuyers(false)
            // ->enableRegisterInSaleGroup(false)
            ->enableAutoApprove(true)
            ->enableEmailNotification(true);
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }

    /**
     * For "RegisterBidder" SOAP call with forceUpdateBidderNumber = 'Y'.
     * Allow approving of flagged users.
     * @param int $userId
     * @param int $auctionId
     * @param string $bidderNumber force assign bidder#. Empty to unset existing bidder#.
     * @param int $editorUserId
     * @return AuctionBidderRegistrator
     */
    public function createSoapAbsoluteRegistrator(
        int $userId,
        int $auctionId,
        string $bidderNumber,
        int $editorUserId
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->assignBidderNumber($bidderNumber)
            ->enableEmailNotification(true)
            ->enableFlaggedUserApprovable(true)
            ->setAuctionId($auctionId)
            ->setEditorUserId($editorUserId)
            ->setUserId($userId);
        if ($this->getBidderNumberPadding()->isNone($bidderNumber)) {
            $config->enableRegisterOnly(true);
        } else {
            $config->enableAbsoluteApprove(true);
        }
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }

    /**
     * For "RegisterBidder" SOAP call with forceUpdateBidderNumber = 'R'
     * @param int $userId
     * @param int $auctionId
     * @param int $editorUserId
     * @return AuctionBidderRegistrator
     */
    public function createSoapRegularRegistrator(
        int $userId,
        int $auctionId,
        int $editorUserId
    ): AuctionBidderRegistrator {
        $config = Config::new()
            ->enableAuthOrCapture(true)
            ->enableAutoApprove(true)
            ->enableEmailNotification(true)
            ->enableRegisterBuyers(true)
            ->enableRegisterInSaleGroup(true)
            ->enableRestrictionByBidderRole(true)
            ->enableRestrictionByUserFlag(true)
            ->setAuctionId($auctionId)
            ->setEditorUserId($editorUserId)
            ->setUserId($userId);
        return $this->createAuctionBidderRegistrator()->setConfig($config);
    }
}
