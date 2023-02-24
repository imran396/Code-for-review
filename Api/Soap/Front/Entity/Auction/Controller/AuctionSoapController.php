<?php

namespace Sam\Api\Soap\Front\Entity\Auction\Controller;

use InvalidArgumentException;
use RuntimeException;
use Sam\Api\Soap\Front\Entity\Auction\Handle\Common\AuctionSoapConstants;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ForceUpdateBidderNumber\RegisterBidderWithForceUpdateBidderNumberHandlerCreateTrait;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Prerequisite\RegisterBidderPrerequisiteInitializerCreateTrait;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\ReadBidderNumber\RegisterBidderWithReadBidderNumberHandlerCreateTrait;
use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\RegularRegistration\RegisterBidderWithRegularRegistrationHandlerCreateTrait;
use Sam\Api\Soap\Front\Entity\Base\Controller\SoapControllerBase;
use Sam\Auction\Delete\Update\AuctionDeleterCreateTrait;
use Sam\AuctionLot\Date\AuctionLotDatesUpdaterCreateTrait;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Bidder\AuctionBidder\AuctionBidderHelperAwareTrait;
use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactoryCreateTrait;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Auction\Dto\AuctionMakerConfigDto;
use Sam\EntityMaker\Auction\Dto\AuctionMakerDtoFactory;
use Sam\EntityMaker\Auction\Dto\AuctionMakerInputDto;
use Sam\EntityMaker\Auction\Lock\AuctionMakerLockerCreateTrait;
use Sam\EntityMaker\Auction\Save\AuctionMakerProducer;
use Sam\EntityMaker\Auction\Validate\AuctionMakerValidator;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * Class Auction
 * @package Sam\Soap
 * @method self init($auth, $namespace)
 */
class AuctionSoapController extends SoapControllerBase
{
    use AuctionBidderHelperAwareTrait;
    use AuctionBidderRegistratorFactoryCreateTrait;
    use AuctionDeleterCreateTrait;
    use AuctionLotDatesUpdaterCreateTrait;
    use AuctionLotReordererAwareTrait;
    use AuctionMakerLockerCreateTrait;
    use BidderNumPaddingAwareTrait;
    use CurrentDateTrait;
    use RegisterBidderPrerequisiteInitializerCreateTrait;
    use RegisterBidderWithForceUpdateBidderNumberHandlerCreateTrait;
    use RegisterBidderWithReadBidderNumberHandlerCreateTrait;
    use RegisterBidderWithRegularRegistrationHandlerCreateTrait;

    /** @var string[] */
    protected array $defaultNamespaces = AuctionSoapConstants::DEFAULT_NAMESPACES;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete auction
     * @param string $key Can be auction id, sale# or the synchronization key
     */
    public function delete(string $key): void
    {
        $auctionNamespaceAdapter = new AuctionNamespaceAdapter(
            (object)['Key' => $key],
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $auction = $auctionNamespaceAdapter->getEntity();
        $this->updateLastSyncIn($key, Constants\EntitySync::TYPE_AUCTION);
        $this->createAuctionDeleter()->delete($auction, $this->editorUserId);
    }

    /**
     * Register Bidder
     *
     * @param string $userKey
     * @param string $auctionKey
     * @param string|null $bidderNumber null - mean that parameter is not set in SOAP call, '' means empty value may lead to auto-generation
     * @param string|null $forceUpdateBidderNumber null - mean that parameter is not set in SOAP call
     * @return string
     */
    public function registerBidder(
        string $userKey,
        string $auctionKey,
        ?string $bidderNumber,
        ?string $forceUpdateBidderNumber = null
    ): string {
        $prerequisiteInitializer = $this->createRegisterBidderPrerequisiteInitializer();
        $success = $prerequisiteInitializer->detect(
            $userKey,
            $auctionKey,
            $forceUpdateBidderNumber,
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        if (!$success) {
            throw new RuntimeException($prerequisiteInitializer->errorMessage);
        }
        $userId = $prerequisiteInitializer->userId;
        $auctionId = $prerequisiteInitializer->auctionId;

        /**
         * Force update bidder# (RegisterBidderSoapConstants::FUBN_YES)
         */
        if ($prerequisiteInitializer->shouldRunForceUpdateBidderNumber()) {
            $result = $this->createRegisterBidderWithForceUpdateBidderNumberHandler()->handle(
                $bidderNumber,
                $userId,
                $auctionId,
                $this->editorUserId
            );
            if ($result->hasError()) {
                throw new RuntimeException($result->errorMessage());
            }
            $this->updateLastSyncIn($auctionKey, Constants\EntitySync::TYPE_AUCTION);
            $this->updateLastSyncIn($userKey, Constants\EntitySync::TYPE_USER);
            return $result->bidderNumber;
        }

        /**
         * Read bidder# (RegisterBidderSoapConstants::FUBN_NO)
         */
        if ($prerequisiteInitializer->shouldRunReadBidderNumber()) {
            $result = $this->createRegisterBidderWithReadBidderNumberHandler()
                ->handle($userId, $auctionId);
            if ($result->hasError()) {
                throw new RuntimeException($result->errorMessage());
            }
            return $result->bidderNumber;
        }

        /**
         * Regular (default) auction bidder registration (RegisterBidderSoapConstants::FUBN_REGULAR)
         */
        $result = $this->createRegisterBidderWithRegularRegistrationHandler()
            ->handle($userId, $auctionId, $this->editorUserId);
        if ($result->hasError()) {
            throw new RuntimeException($result->errorMessage());
        }
        $this->updateLastSyncIn($auctionKey, Constants\EntitySync::TYPE_AUCTION);
        $this->updateLastSyncIn($userKey, Constants\EntitySync::TYPE_USER);
        return $result->bidderNumber;
    }

    /**
     * Reorder auction lots
     * @param string $id
     */
    public function reorderAuctionLots(string $id): void
    {
        $auctionNamespaceAdapter = new AuctionNamespaceAdapter(
            (object)['Key' => $id],
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $auction = $auctionNamespaceAdapter->getEntity();

        $this->getAuctionLotReorderer()->reorder($auction, $this->editorUserId);
        $this->updateLastSyncIn($id, Constants\EntitySync::TYPE_AUCTION);
    }

    /**
     * Refresh auction lots dates
     * @param string $id
     */
    public function refreshAuctionLotDates(string $id): void
    {
        $auctionNamespaceAdapter = new AuctionNamespaceAdapter(
            (object)['Key' => $id],
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $data = $auctionNamespaceAdapter->toObject();

        $this->createAuctionLotDatesUpdater()->update($data->Id, $this->editorUserId);
        $this->updateLastSyncIn($id, Constants\EntitySync::TYPE_AUCTION);
    }

    /**
     * Save an Auction
     *
     * Missing fields keep their content,
     * Empty fields will remove the field content
     *
     * @param object $data
     * @return int
     */
    public function save($data): int
    {
        $auctionNamespaceAdapter = new AuctionNamespaceAdapter(
            $data,
            $this->namespace,
            $this->namespaceId,
            $this->editorUserAccountId
        );
        $data = $auctionNamespaceAdapter->toObject();

        $this->parseAdditionalCurrencies($data);
        $this->parseBuyersPremiums($data);
        $this->parseCustomFields($data, 'AuctionCustomFields');
        $this->parseIncrements($data);
        $this->parseRanges($data, 'ConsignorCommissionRanges');
        $this->parseRanges($data, 'ConsignorUnsoldFeeRanges');
        $this->parseRanges($data, 'ConsignorSoldFeeRanges');
        $this->parseTaxStates($data);

        /**
         * @var AuctionMakerInputDto $auctionInputDto
         * @var AuctionMakerConfigDto $auctionConfigDto
         */
        [$auctionInputDto, $auctionConfigDto] = AuctionMakerDtoFactory::new()
            ->createDtos(Mode::SOAP, $this->editorUserId, $this->editorUserAccountId, $this->editorUserAccountId);
        $auctionInputDto->setArray((array)$data);

        $lockingResult = $this->createAuctionMakerLocker()->lock($auctionInputDto, $auctionConfigDto); // #a-lock-5
        if (!$lockingResult->isSuccess()) {
            throw new RuntimeException($lockingResult->message());
        }

        try {
            $validator = AuctionMakerValidator::new()->construct($auctionInputDto, $auctionConfigDto);
            if ($validator->validate()) {
                $producer = AuctionMakerProducer::new()->construct($auctionInputDto, $auctionConfigDto);
                $producer->produce();
                return $producer->getAuction()->Id;
            }
        } finally {
            $this->createAuctionMakerLocker()->unlock($auctionConfigDto); // #a-lock-5, unlock after success or failed save, incl. validation
        }

        $logData = ['a' => $data->Id ?? 0, 'editor u' => $this->editorUserId];
        $errorMessages = array_merge($validator->getMainErrorMessages(), $validator->getCustomFieldsErrors());
        log_debug(implode("\n", $errorMessages) . composeSuffix($logData));
        throw new InvalidArgumentException(implode("\n", $errorMessages));
    }

    /**
     * Parse additionalCurrencies from AdditionalCurrencies, currency tags
     * @param $data
     */
    protected function parseAdditionalCurrencies($data): void
    {
        if (isset($data->AdditionalCurrencies->Currency)) {
            $data->AdditionalCurrencies = (array)$data->AdditionalCurrencies->Currency;
        }
    }
}
