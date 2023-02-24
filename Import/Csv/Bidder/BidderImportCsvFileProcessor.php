<?php
/**
 * SAM-3796: Bidder upload into auction
 * SAM-9366: Refactor Sam\Bidder\AuctionBidder\CsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\Bidder;

use Auction;
use InvalidArgumentException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Bidder\AuctionBidder\Register\General\AuctionBidderRegistratorFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\User\Lock\UserMakerLockerCreateTrait;
use Sam\Import\Csv\Base\ImportCsvFileProcessorInterface;
use Sam\Import\Csv\Base\ImportCsvProcessResult;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Bidder\Internal\DetectUser\UserDetectorCreateTrait;
use Sam\Import\Csv\Bidder\Internal\Dto\DtoFactoryCreateTrait;
use Sam\Import\Csv\Bidder\Internal\Dto\RowInput;
use Sam\Import\Csv\Bidder\Internal\Process\UserProducerCreateTrait;
use Sam\Import\Csv\Bidder\Internal\Validate\ValidatorCreateTrait;
use Sam\Import\Csv\Read\CsvFileReaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for handling uploaded CSV file with bidders info.
 *
 * Class BidderImportCsvFileProcessor
 * @package Sam\Import\Csv\Bidder
 */
class BidderImportCsvFileProcessor extends CustomizableClass implements ImportCsvFileProcessorInterface
{
    use AdminTranslatorAwareTrait;
    use AuctionBidderRegistratorFactoryCreateTrait;
    use AuctionLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;
    use CsvFileReaderAwareTrait;
    use DtoFactoryCreateTrait;
    use UserDetectorCreateTrait;
    use UserMakerLockerCreateTrait;
    use UserProducerCreateTrait;
    use ValidatorCreateTrait;

    protected int $systemAccountId;
    protected int $editorUserId;
    protected Auction $auction;
    protected bool $sendRegistrationAndApprovalEmail;
    protected bool $autoApproveBidder;
    protected int $syncMode;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(
        int $editorUserId,
        string $sourceFilePath,
        int $auctionId,
        bool $sendRegistrationAndApprovalEmail,
        bool $autoApprove,
        int $syncMode,
        int $accountId,
        string $encoding = 'UTF-8'
    ): static {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            throw new InvalidArgumentException("Auction not found by id \"{$auctionId}\"");
        }
        $this->auction = $auction;
        $this->systemAccountId = $accountId;
        $this->editorUserId = $editorUserId;
        $this->sendRegistrationAndApprovalEmail = $sendRegistrationAndApprovalEmail;
        $this->autoApproveBidder = $autoApprove;
        $this->syncMode = $syncMode;

        $this->getCsvFileReader()->construct(
            $sourceFilePath,
            $encoding,
            array_flip($this->getCsvColumnNames())
        );
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function validate(): ImportCsvValidationResult
    {
        $result = $this->createValidator()->validateHeader(
            $this->readHeader(),
            $this->getCsvColumnNames(),
        );
        return $result;
    }

    /**
     * @inheritdoc
     */
    public function process(int $offset): ImportCsvProcessResult
    {
        $userProducer = $this->createUserProducer();
        $auctionBidderRegistratorFactory = $this->createAuctionBidderRegistratorFactory();
        $rows = $this->readRows($offset);
        foreach ($rows as $rowIndex => $row) {
            $lockingResult = $this->createUserMakerLocker()->lock($row->userInputDto, $row->userConfigDto); // #user-lock-10
            if (!$lockingResult->isSuccess()) {
                return ImportCsvProcessResult::new()->errorOnRow(
                    [$this->getAdminTranslator()->trans('import.csv.user.user_entity_maker_lock_error', [], 'admin_message')],
                    $rowIndex
                );
            }
            try {
                $validationResult = $this->createValidator()->validateRow(
                    $row,
                    $rowIndex,
                    $this->getCsvColumnNames(),
                    $this->auction->Id,
                    $this->syncMode,
                    $this->getCsvFileReader()->getEncoding()
                );
                if ($validationResult->hasError()) {
                    return ImportCsvProcessResult::new()->errorOnRow($validationResult->getErrorMessages(), $rowIndex);
                }

                $user = $userProducer->produce($row->userInputDto, $row->userConfigDto, $this->auction->AccountId);
                $registrator = $auctionBidderRegistratorFactory->createCsvAuctionBidderImport(
                    $user->Id,
                    $this->auction->Id,
                    $row->bidderNo,
                    $this->autoApproveBidder,
                    $this->sendRegistrationAndApprovalEmail,
                    $row->userConfigDto->editorUserId
                );
                $registrator->register();
                $errorMessage = $registrator->getErrorMessage();
                if ($errorMessage) {
                    return ImportCsvProcessResult::new()->error([$errorMessage]);
                }
            } finally {
                $this->createUserMakerLocker()->unlock($row->userConfigDto); // #user-lock-10, unlock after success or failed save
            }
        }
        return ImportCsvProcessResult::new()->success();
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return $this->getCsvFileReader()->countRows();
    }

    /**
     * @param int|null $offset
     * @return RowInput[]
     */
    protected function readRows(?int $offset = null): iterable
    {
        $dtoFactory = $this->createDtoFactory();
        $csvRows = $this->getCsvFileReader()->readRows($offset);
        foreach ($csvRows as $rowIndex => $row) {
            log_info('populate dto from line ' . $rowIndex);
            $userId = $this->createUserDetector()->detectUserId($row, $this->syncMode);
            yield $rowIndex => $dtoFactory->create(
                $row,
                $userId,
                $this->editorUserId,
                $this->auction->AccountId,
                $this->systemAccountId,
            );
        }
    }

    protected function readHeader(): array
    {
        return $this->getCsvFileReader()->readHeader();
    }

    protected function getCsvColumnNames(): array
    {
        return $this->cfg()->get('csv->admin->bidder')->toArray();
    }
}
