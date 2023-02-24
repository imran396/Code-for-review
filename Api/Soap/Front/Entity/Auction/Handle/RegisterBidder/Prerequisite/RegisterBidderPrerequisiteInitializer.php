<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Prerequisite;

use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Common\RegisterBidderSoapConstants;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Prerequisite\Internal\FindTarget\RegisterBidderTargetFinderCreateTrait;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\NormalizeInput\RegisterBidderInputNormalizer;
use Sam\Core\Service\CustomizableClass;

/**
 * Class RegisterBidderPrerequisiteInitializer
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Prerequisite
 */
class RegisterBidderPrerequisiteInitializer extends CustomizableClass
{
    use RegisterBidderTargetFinderCreateTrait;

    /** @var int|null */
    public ?int $userId = null;
    /** @var int|null */
    public ?int $auctionId = null;
    /** @var string */
    public string $forceUpdateBidderNumber = '';
    /** @var string */
    public string $errorMessage = '';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate initial prerequisites and return error message in case of problem, or empty string in case of success.
     * @param string $userKey
     * @param string $auctionKey
     * @param string|null $forceUpdateBidderNumber
     * @param string $namespace
     * @param int|null $namespaceId
     * @param int|null $systemAccountId
     * @return bool
     */
    public function detect(
        string $userKey,
        string $auctionKey,
        ?string $forceUpdateBidderNumber,
        string $namespace,
        ?int $namespaceId,
        ?int $systemAccountId
    ): bool {
        $targetFinder = $this->createRegisterBidderTargetFinder();
        $userId = $targetFinder->findUserId($userKey, $namespace, $namespaceId, $systemAccountId);
        if (!$userId) {
            $this->errorMessage = sprintf(RegisterBidderSoapConstants::ERR_MSG_USER_NOT_FOUND_WITHING_SYNC_NAMESPACE, $userKey, $namespace);
            return false;
        }
        $this->userId = $userId;

        $auctionId = $targetFinder->findAuctionId($auctionKey, $namespace, $namespaceId, $systemAccountId);
        if (!$auctionId) {
            $this->errorMessage = sprintf(RegisterBidderSoapConstants::ERR_MSG_AUCTION_NOT_FOUND_WITHING_SYNC_NAMESPACE, $auctionKey, $namespace);
            return false;
        }
        $this->auctionId = $auctionId;

        $forceUpdateBidderNumberNorm = RegisterBidderInputNormalizer::new()->normalizeForceUpdateBidderNumber($forceUpdateBidderNumber);
        if (!$forceUpdateBidderNumberNorm) {
            $this->errorMessage = sprintf(RegisterBidderSoapConstants::ERR_MSG_INVALID_VALUE_FOR_FORCE_UPDATE_BIDDER_NUMBER_OPTION, $forceUpdateBidderNumber);
            return false;
        }
        $this->forceUpdateBidderNumber = $forceUpdateBidderNumberNorm;

        return true;
    }

    public function shouldRunForceUpdateBidderNumber(): bool
    {
        return $this->forceUpdateBidderNumber === RegisterBidderSoapConstants::FUBN_YES;
    }

    public function shouldRunReadBidderNumber(): bool
    {
        return $this->forceUpdateBidderNumber === RegisterBidderSoapConstants::FUBN_NO;
    }
}
