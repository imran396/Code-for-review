<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Entity\Restore\Cli\Command\Handler;

use Auction;
use AuctionCustData;
use Sam\Auction\Delete\Restore\AuctionUndeleterCreateTrait;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\AuctionCustField\AuctionCustFieldReadRepositoryCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Handling a request for restoring a soft-deleted auction
 *
 * Class AuctionRestoreCommandHandler
 * @package Sam\Entity\Restore\Cli\Command\Handler
 */
class AuctionRestoreCommandHandler extends CustomizableClass implements EntityRestoreCommandHandlerInterface
{
    use AuctionCustFieldReadRepositoryCreateTrait;
    use AuctionUndeleterCreateTrait;
    use ResultStatusCollectorAwareTrait;
    use UserLoaderAwareTrait;

    public const ERR_AUCTION_NOT_RESTORED = 1;
    public const SUCCESS_AUCTION_RESTORED = 1;
    public const SUCCESS_CUSTOM_DATA_RESTORED = 2;
    public const WARN_CUSTOM_DATA_NOT_RESTORED = 1;

    protected ?array $auctionCustomFieldsCache = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEntityName(): string
    {
        return 'Auction';
    }

    /**
     * @inheritDoc
     */
    public function restore(int $entityId): EntityRestoreCommandHandlerResponse
    {
        $this->initResultStatusCollector();

        $result = $this->createAuctionUndeleter()->undelete($entityId, $this->getUserLoader()->loadSystemUserId());

        $auction = $result->getRestoredAuction();
        if (
            $auction
            && $result->hasSuccess()
        ) {
            $this->addAuctionRestoredSuccessMessage($auction);
        }

        if ($result->hasError()) {
            $this->addAuctionNotRestoredErrorMessage($result->errorMessage());
        }

        if ($result->getCustomDataRestoreResult()) {
            $restoredCustomFieldsData = $result->getCustomDataRestoreResult()->getRestored();
            if ($restoredCustomFieldsData) {
                $this->addCustomDataRestoredSuccessMessage($restoredCustomFieldsData);
            }

            $restoredCustomFieldsDataMessages = $result->getCustomDataRestoreResult()->getWarnings();
            if ($restoredCustomFieldsDataMessages) {
                $this->addCustomDataNotRestoredInfoMessage($restoredCustomFieldsDataMessages);
            }
        }
        $response = EntityRestoreCommandHandlerResponse::new()->construct($this->getResultStatusCollector());
        return $response;
    }

    /**
     * @param Auction $auction
     */
    protected function addAuctionRestoredSuccessMessage(Auction $auction): void
    {
        $message = __(
            'entity.restore.cli.command.handler.auction.success_auction_restored',
            [
                'auctionName' => $auction->Name,
                'auctionId' => $auction->Id
            ],
            'admin_message'
        );
        $this->getResultStatusCollector()->addSuccess(self::SUCCESS_AUCTION_RESTORED, $message);
    }

    /**
     * @param string $message
     */
    protected function addAuctionNotRestoredErrorMessage(string $message): void
    {
        $this->getResultStatusCollector()->addError(self::ERR_AUCTION_NOT_RESTORED, $message);
    }

    /**
     * @param ResultStatus[] $notRestoredCustomDataWarnings
     */
    protected function addCustomDataNotRestoredInfoMessage(array $notRestoredCustomDataWarnings): void
    {
        $warningMessages = array_map(
            function (ResultStatus $resultStatus) {
                return $resultStatus->getMessage() . ' (' . $this->makeCustomDataValue($resultStatus->getPayload()['entity']) . ')';
            },
            $notRestoredCustomDataWarnings
        );
        $this->getResultStatusCollector()->addInfo(self::WARN_CUSTOM_DATA_NOT_RESTORED, implode("\n", $warningMessages));
    }

    /**
     * @param AuctionCustData[] $restoredCustomData
     */
    protected function addCustomDataRestoredSuccessMessage(array $restoredCustomData): void
    {
        $values = array_map(
            function (AuctionCustData $customData): string {
                return "    *" . $this->makeCustomDataValue($customData);
            },
            $restoredCustomData
        );
        $message = __('entity.restore.cli.command.handler.auction.success_custom_data_restored', [], 'admin_message');
        $message .= "\n" . implode("\n", $values);
        $this->getResultStatusCollector()->addSuccess(self::SUCCESS_CUSTOM_DATA_RESTORED, $message);
    }

    /**
     * @param AuctionCustData $customData
     * @return string
     */
    protected function makeCustomDataValue(AuctionCustData $customData): string
    {
        $fieldName = $this->getCustomFieldName($customData->AuctionCustFieldId);
        $value = "ID: {$customData->Id} Field: {$fieldName} (ID {$customData->AuctionCustFieldId})";
        if ($customData->Numeric !== null) {
            $value .= " Numeric: '{$customData->Numeric}'";
        }
        if ($customData->Text !== null) {
            $text = str_replace("\r", '', $customData->Text);
            $value .= " Text: '{$text}'";
        }
        return $value;
    }

    /**
     * @param int $fieldId
     * @return string
     */
    protected function getCustomFieldName(int $fieldId): string
    {
        $customFields = $this->loadAllAuctionCustomFields();
        return $customFields[$fieldId]['name'] ?? '';
    }

    /**
     * @return array
     */
    protected function loadAllAuctionCustomFields(): array
    {
        if ($this->auctionCustomFieldsCache === null) {
            $fields = $this->createAuctionCustFieldReadRepository()
                ->select(['id', 'name'])
                ->loadRows();
            $this->auctionCustomFieldsCache = ArrayHelper::produceIndexedArray($fields, 'id');
        }

        return $this->auctionCustomFieldsCache;
    }

    protected function initResultStatusCollector(): void
    {
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_AUCTION_NOT_RESTORED => '',
            ],
            [
                self::SUCCESS_AUCTION_RESTORED => '',
                self::SUCCESS_CUSTOM_DATA_RESTORED => '',
            ],
            [],
            [
                self::WARN_CUSTOM_DATA_NOT_RESTORED => '',
            ]
        );
    }
}
