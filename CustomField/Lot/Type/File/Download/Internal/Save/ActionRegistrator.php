<?php
/**
 * Perform necessary actions before download file:
 * - register download action in log;
 * - send email.
 *
 * SAM-5608: Refactor lot custom field file download for web
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           07/01/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Type\File\Download\Internal\Save;

use AuctionLotItem;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Email_Template;
use LotItem;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\CustomField\Lot\Type\File\Download\Internal\Load\DataLoaderAwareTrait;
use Sam\Date\DateHelperAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserDocumentViews\UserDocumentViewsWriteRepositoryAwareTrait;
use User;
use Sam\Core\Constants;

/**
 * @package Sam\CustomField\Lot\Type\File\Download
 * @internal
 */
class ActionRegistrator extends CustomizableClass
{
    use DataLoaderAwareTrait;
    use DateHelperAwareTrait;
    use EditorUserAwareTrait;
    use EntityFactoryCreateTrait;
    use UserDocumentViewsWriteRepositoryAwareTrait;

    protected LotItem $lotItem;
    protected string $fileName;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return self::_new(self::class);
    }

    /**
     * @param int|null $editorUserId
     * @param LotItem $lotItem
     * @param string $fileName
     * @return $this
     */
    public function construct(?int $editorUserId, LotItem $lotItem, string $fileName): static
    {
        $this->setEditorUserId($editorUserId);
        $this->lotItem = $lotItem;
        $this->fileName = $fileName;
        return $this;
    }

    public function register(): void
    {
        $editorUser = $this->getEditorUser();
        if (!$editorUser) {
            $logData = [
                'li' => $this->lotItem->Id,
                'filename' => $this->fileName,
            ];
            log_debug("Don't register custom field file downloading action for anonymous user" . composeSuffix($logData));
            return;
        }
        $dataLoader = $this->getDataLoader();
        $auctionLots = $dataLoader->loadAuctionLotsByLotItemId($this->lotItem->Id);
        $this->saveUserDocumentViewForLots($auctionLots, $this->lotItem->Id, $this->fileName, $this->getEditorUserId());
        $this->addToActionQueue($auctionLots, $this->lotItem, $this->fileName, $editorUser);
    }

    /**
     * @param AuctionLotItem[] $auctionLots
     * @param int $lotItemId
     * @param string $fileName
     * @param int|null $editorUserId
     */
    protected function saveUserDocumentViewForLots(
        array $auctionLots,
        int $lotItemId,
        string $fileName,
        ?int $editorUserId
    ): void {
        if (!$editorUserId) {
            return;
        }
        $dataLoader = $this->getDataLoader();
        $lotCustomData = $dataLoader->loadLotCustomDataByLotItemIdAndFilename($lotItemId, $fileName);
        if (!$lotCustomData) {
            log_error("Lot item custom data not found" . composeSuffix(['li' => $lotItemId, 'filename' => $fileName]));
            return;
        }
        foreach ($auctionLots as $auctionLot) {
            $this->insertViewLog(
                $editorUserId,
                $auctionLot->Id,
                $lotCustomData->Id,
                $fileName,
                $editorUserId
            );
        }
    }

    /**
     * @param int $userId
     * @param int $auctionLotId
     * @param int $lotCustomDataId
     * @param string $documentName
     * @param int $editorUserId
     */
    protected function insertViewLog(
        int $userId,
        int $auctionLotId,
        int $lotCustomDataId,
        string $documentName,
        int $editorUserId
    ): void {
        $view = $this->createEntityFactory()->userDocumentViews();
        $view->UserId = $userId;
        $view->AuctionLotItemId = $auctionLotId;
        $view->LotItemCustDataId = $lotCustomDataId;
        $view->DocumentName = $documentName;
        $view->DateViewed = $this->getDateHelper()->detectCurrentDateUtc();
        $this->getUserDocumentViewsWriteRepository()->saveWithModifier($view, $editorUserId);
    }

    /**
     * @param array $auctionLots
     * @param LotItem $lotItem
     * @param string $fileName
     * @param User $editorUser
     */
    protected function addToActionQueue(
        array $auctionLots,
        LotItem $lotItem,
        string $fileName,
        User $editorUser
    ): void {
        $documentValues = [
            'strDocumentLink' => '/downloads/lot/' . $lotItem->Id . '/' . $fileName,
            'strDocumentType' => pathinfo($fileName, PATHINFO_EXTENSION)
        ];

        foreach ($auctionLots as $auctionLot) {
            $emailManager = Email_Template::new()->construct(
                $lotItem->AccountId,
                Constants\EmailKey::DOWNLOAD_DOCUMENT_CUSTOM_FIELD,
                $editorUser->Id,
                [$editorUser, $lotItem, $auctionLot, $documentValues],
                $lotItem->AuctionId
            );
            $emailManager->addToActionQueue(Constants\ActionQueue::MEDIUM);
        }
    }
}
