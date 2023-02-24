<?php
/**
 * SAM-9360: Refactor \Lot_PostCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\PostAuction;

use Auction;
use Exception;
use InvalidArgumentException;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\EntityMaker\User\Lock\UserMakerLockerCreateTrait;
use Sam\Import\Csv\Base\ImportCsvFileProcessorInterface;
use Sam\Import\Csv\Base\ImportCsvProcessResult;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\PostAuction\Internal\Dto\RowInput;
use Sam\Import\Csv\PostAuction\Internal\Dto\RowInputFactoryCreateTrait;
use Sam\Import\Csv\PostAuction\Internal\Process\RowProcessorCreateTrait;
use Sam\Import\Csv\PostAuction\Internal\Process\Username\UniqueUsernameGeneratorCreateTrait;
use Sam\Import\Csv\PostAuction\Internal\Validate\ContentValidatorCreateTrait;
use Sam\Import\Csv\PostAuction\Statistic\PostAuctionImportCsvProcessStatistic;
use Sam\Import\Csv\Read\CsvFileReaderAwareTrait;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for handling uploaded CSV file.
 *
 * Class BidImportCsvFileProcessor
 * @package Sam\Import\Csv\PostAuction
 */
class PostAuctionImportCsvFileProcessor extends CustomizableClass implements ImportCsvFileProcessorInterface
{
    use AdminTranslatorAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotReordererAwareTrait;
    use ContentValidatorCreateTrait;
    use CsvFileReaderAwareTrait;
    use OptionalsTrait;
    use RowInputFactoryCreateTrait;
    use RowProcessorCreateTrait;
    use SystemAccountAwareTrait;
    use UniqueUsernameGeneratorCreateTrait;
    use UserMakerLockerCreateTrait;

    public const OP_COLUMN_HEADERS = 'columnHeaders';

    protected int $editorUserId;
    protected float $premium;
    protected bool $isUnassignUnsold;
    protected Auction $auction;
    /**
     * @var string[]
     */
    protected array $errorMessages = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param string $sourceFilePath
     * @param int $auctionId
     * @param string $encoding
     * @param float $premium
     * @param bool $isUnassignUnsold
     * @param array $optionals
     * @return static
     */
    public function construct(
        int $editorUserId,
        string $sourceFilePath,
        int $auctionId,
        string $encoding = 'UTF-8',
        float $premium = 0.,
        bool $isUnassignUnsold = false,
        array $optionals = []
    ): static {
        $this->editorUserId = $editorUserId;
        $this->premium = $premium;
        $this->isUnassignUnsold = $isUnassignUnsold;
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            throw new InvalidArgumentException("Available auction not found by id \"{$auctionId}\"");
        }
        $this->auction = $auction;

        $this->initOptionals($optionals);
        $this->getCsvFileReader()->construct(
            $sourceFilePath,
            $encoding,
            array_flip($this->fetchOptional(self::OP_COLUMN_HEADERS))
        );
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function process(int $offset): ImportCsvProcessResult
    {
        $statistic = PostAuctionImportCsvProcessStatistic::new();

        $dtos = $this->readRows($offset);
        $bidImportCsvRowProcessor = $this->createRowProcessor();
        foreach ($dtos as $rowIndex => $dto) {
            $lockingResult = $this->createUserMakerLocker()->lock($dto->userInputDto, $dto->userConfigDto); // #user-lock-5
            $translator = $this->getAdminTranslator();
            if (!$lockingResult->isSuccess()) {
                $message = $translator->trans(
                    'import.csv.general.producing_error',
                    [
                        'rowIndex' => $rowIndex,
                        'errorMessage' => $translator->trans('import.csv.post_auction.user_entity_maker_lock_error', [], 'admin_message')
                    ],
                    'admin_message'
                );
                log_warning($message);
                return ImportCsvProcessResult::new()->error([$message], $statistic);
            }
            try {
                $shouldGenerateUsername = !$dto->userInputDto->id && $dto->userInputDto->email;
                if ($shouldGenerateUsername) {
                    $dto->userInputDto->username = $this->createUniqueUsernameGenerator()->generate($dto->userInputDto->username, $dto->userInputDto->email);
                }
                $userValidationResult = $this->createSoldLotInfoImportCsvValidator()->validateUser($dto, $rowIndex);
                if ($userValidationResult->hasError()) {
                    return ImportCsvProcessResult::new()->errorOnRow($userValidationResult->getErrorMessages(), $rowIndex);
                }
                $result = $bidImportCsvRowProcessor->process(
                    $dto,
                    $this->auction->Id,
                    $this->premium,
                    $this->editorUserId,
                    $this->isUnassignUnsold
                );
                if ($result->hasError()) {
                    continue;
                }
                $statistic->processedLotsQuantity++;
                if (
                    $result->winningUser
                    && $result->winningUser->Flag !== Constants\User::FLAG_NONE
                ) {
                    $statistic->flaggedUsers[] = $result->winningUser;
                }
            } catch (Exception $e) {
                $message = $translator->trans(
                    'import.csv.general.producing_error',
                    [
                        'rowIndex' => $rowIndex,
                        'errorMessage' => $e->getMessage()
                    ],
                    'admin_message'
                );
                log_warning($message);
                return ImportCsvProcessResult::new()->error([$message], $statistic);
            } finally {
                $this->createUserMakerLocker()->unlock($dto->userConfigDto); // #user-lock-5, unlock after success or failed save or validation
            }
        }
        $this->getAuctionLotReorderer()->reorder($this->auction, $this->editorUserId);

        return ImportCsvProcessResult::new()->success($statistic);
    }

    /**
     * @inheritdoc
     */
    public function validate(): ImportCsvValidationResult
    {
        $result = $this->createSoldLotInfoImportCsvValidator()->validateHeaderAndRows(
            $this->readRows(),
            $this->getCsvFileReader()->readHeader(),
            $this->fetchOptional(self::OP_COLUMN_HEADERS)
        );
        return $result;
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
    protected function readRows(int $offset = null): iterable
    {
        $bidImportCsvDtoFactory = $this->createRowInputFactory();
        $rows = $this->getCsvFileReader()->readRows($offset);
        foreach ($rows as $rowIndex => $row) {
            if ($row->isEmpty()) {
                continue;
            }
            yield $rowIndex => $bidImportCsvDtoFactory->create(
                $row,
                $this->editorUserId,
                $this->auction->AccountId,
                $this->getSystemAccountId()
            );
        }
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_COLUMN_HEADERS] = $optionals[self::OP_COLUMN_HEADERS]
            ?? static function (): array {
                return ConfigRepository::getInstance()->get('csv->admin->postAuction')->toArray();
            };
        $this->setOptionals($optionals);
    }
}
