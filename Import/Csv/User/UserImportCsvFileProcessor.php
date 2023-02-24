<?php
/**
 * Custom methods can be used there or in customized class (SAM-1573)
 *
 * Optional method called for validation before saving imported data
 * param UserCustField $userCustomField the custom user field object
 * param string $value the value to be checked
 * param string $encoding the encoding of imported data
 * return string the error message or empty in case of successful validation
 * public function UserCustomField_{Field name}_Validate(UserCustField $userCustomField, $value, $encoding)
 *
 * Optional method called when saving custom field
 * param UserCustField $userCustomField the custom user field object
 * param UserCustData $userCustomData the custom user field data
 * param string $value the value to be saved
 * param string $encoding the encoding of imported data
 * public function UserCustomField_{Field name}_Save(UserCustField $userCustomField, UserCustData $userCustomData, $value, $encoding)
 *
 * {Field name} - Camel cased name of custom field (see TextTransformer::toCamelCase())
 */

namespace Sam\Import\Csv\User;

use Exception;
use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\EntityMaker\User\Lock\UserMakerLockerCreateTrait;
use Sam\EntityMaker\User\Save\UserMakerProducer;
use Sam\Import\Csv\Base\ImportCsvFileProcessorInterface;
use Sam\Import\Csv\Base\ImportCsvProcessResult;
use Sam\Import\Csv\Base\ImportCsvValidationResult;
use Sam\Import\Csv\Read\CsvFileReaderAwareTrait;
use Sam\Import\Csv\User\Statistic\UserImportCsvProcessStatistic;
use Sam\Installation\Config\Repository\ConfigRepository;
use Sam\Translation\AdminTranslatorAwareTrait;
use UserCustField;

/**
 * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
 * This might include adding, changing, or moving columns,
 * modifying header names,
 * modifying data or data format(s)
 *
 * Class UserImportCsvFileProcessor
 * @package Sam\User\Import
 */
class UserImportCsvFileProcessor extends CustomizableClass implements ImportCsvFileProcessorInterface
{
    use AdminTranslatorAwareTrait;
    use CsvFileReaderAwareTrait;
    use EditorUserAwareTrait;
    use OptionalsTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserImportCsvDtoFactoryCreateTrait;
    use UserImportCsvValidatorCreateTrait;
    use UserMakerLockerCreateTrait;

    public const OP_COLUMN_HEADERS = 'columnHeaders';

    protected int $accountId;
    protected bool $clearEmptyFields;
    /**
     * @var UserCustField[]
     */
    protected ?array $userCustomFields = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $editorUserId
     * @param string $sourceFilePath
     * @param int $accountId
     * @param string $encoding
     * @param bool $clearEmptyFields
     * @param array $optionals
     * @return static
     */
    public function construct(
        int $editorUserId,
        string $sourceFilePath,
        int $accountId,
        string $encoding = 'UTF-8',
        bool $clearEmptyFields = false,
        array $optionals = []
    ): static {
        $this->accountId = $accountId;
        $this->clearEmptyFields = $clearEmptyFields;
        $this->setEditorUserId($editorUserId);
        $this->initOptionals($optionals);
        $this->getCsvFileReader()->construct(
            $sourceFilePath,
            $encoding,
            array_flip($this->fetchOptional(self::OP_COLUMN_HEADERS))
        );
        return $this;
    }

    /**
     * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
     * This might include adding, changing, or moving columns,
     * modifying header names,
     * modifying data or data format(s)
     *
     * @inheritdoc
     */
    public function process(int $offset): ImportCsvProcessResult
    {
        $statistic = UserImportCsvProcessStatistic::new();

        $rows = $this->readRows($offset);
        foreach ($rows as $rowIndex => [$userInputDto, $userConfigDto]) {
            $lockingResult = $this->createUserMakerLocker()->lock($userInputDto, $userConfigDto); // #user-lock-6
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
                $validator = $this->createUserImportCsvValidator()->validateRow($userInputDto, $userConfigDto, $rowIndex);
                if ($validator->hasError()) {
                    return ImportCsvProcessResult::new()->errorOnRow($validator->getErrorMessages(), $rowIndex);
                }

                if ($userInputDto->id) {
                    $statistic->updatedUsersQuantity++;
                } else {
                    $statistic->addedUsersQuantity++;
                }
                $userProducer = UserMakerProducer::new()->construct($userInputDto, $userConfigDto);
                $userProducer->produce();

                foreach ($userProducer->getAddedCustomFieldFiles() as $id => $fileNames) {
                    $statistic->customFieldFiles[$id] = $fileNames;
                }
            } catch (Exception $e) {
                log_warning(sprintf('Error occurred on row %d csv lot upload %s', $rowIndex, $this->getCsvFileReader()->getSourceFilePath()) . $e->getMessage());
                return ImportCsvProcessResult::new()->error([$e->getMessage()], $statistic);
            } finally {
                $this->createUserMakerLocker()->unlock($userConfigDto); // #user-lock-6, unlock after success or failed save
            }
        }

        return ImportCsvProcessResult::new()->success($statistic);
    }

    /**
     * @inheritdoc
     */
    public function validate(): ImportCsvValidationResult
    {
        /* -------------------------------------
         * IMPORTANT NOTE: Report any changes of format to your manager and in the ticket you are working on!
         * This might include adding, changing, or moving columns,
         * modifying header names,
         * modifying data or data format(s)
         * ------------------------------------- */
        $customFields = $this->loadUserCustomFields();
        $result = $this->createUserImportCsvValidator()->validateHeader(
            $this->getCsvFileReader()->readHeader(),
            $this->accountId,
            $customFields
        );
        return $result;
    }

    public function count(): int
    {
        return $this->getCsvFileReader()->countRows();
    }

    /**
     * Create User Dto object for each table row
     * @param int|null $offset
     * @return iterable
     */
    protected function readRows(?int $offset = null): iterable
    {
        $userImportCsvDtoFactory = $this->createUserImportCsvDtoFactory()->construct(
            $this->getEditorUserId(),
            $this->accountId,
            $this->loadUserCustomFields(),
            $this->clearEmptyFields
        );

        foreach ($this->getCsvFileReader()->readRows($offset) as $rowIndex => $row) {
            if ($row->isEmpty()) {
                continue;
            }
            log_info('populate dto from line ' . $rowIndex);
            yield $rowIndex => $userImportCsvDtoFactory->create($row);
        }
    }

    /**
     * @return UserCustField[]
     */
    protected function loadUserCustomFields(): array
    {
        if ($this->userCustomFields === null) {
            $this->userCustomFields = $this->getUserCustomFieldLoader()->loadAllEditable([], true);
        }
        return $this->userCustomFields;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_COLUMN_HEADERS] = $optionals[self::OP_COLUMN_HEADERS]
            ?? static function (): array {
                return ConfigRepository::getInstance()->get('csv->admin->user')->toArray();
            };
        $this->setOptionals($optionals);
    }
}
