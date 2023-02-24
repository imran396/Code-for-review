<?php
/**
 * SAM-6308: Refactor custom field management to separate modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\CustomField\Lot\Cli\Command;

use LotItemCustField;
use Sam\Core\Constants;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Cli command handler for getting settings of lot item custom field
 *
 * Class GetLotCustomFieldCommand
 * @package Sam\CustomField\Lot\Cli\Command
 */
class GetLotCustomFieldCommand extends Command
{
    use LotItemCustFieldReadRepositoryCreateTrait;

    public const NAME = 'get';

    public function __construct(?string $name = null)
    {
        if ($name === null) {
            $name = static::NAME;
        }
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Specify custom field id to get setting');
        $this->addOption('name', null, InputOption::VALUE_OPTIONAL, 'Specify custom field name to get setting');
        $this->setDescription('This command gets settings of lot item custom field');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customField = $this->loadLotItemCustomField($input);
        $fields = $this->convertEntityToArray($customField);
        $io = new SymfonyStyle($input, $output);
        $this->display($fields, $io);
        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param InputInterface $input
     * @return LotItemCustField
     */
    private function loadLotItemCustomField(InputInterface $input): LotItemCustField
    {
        if ($input->getOption('id') !== null) {
            $id = (int)$input->getOption('id');
            $lotCustomField = $this->createLotItemCustFieldReadRepository()
                ->filterId($id)
                ->loadEntity();
            if (!$lotCustomField) {
                throw new \RuntimeException(sprintf('Lot custom field with id "%s" not found.', $id));
            }
        } elseif ($input->getOption('name') !== null) {
            $name = $input->getOption('name');
            $lotCustomField = $this->createLotItemCustFieldReadRepository()
                ->filterName($name)
                ->loadEntity();
            if (!$lotCustomField) {
                throw new \RuntimeException(sprintf('Lot custom field with name "%s" not found.', $name));
            }
        } else {
            throw new \RuntimeException('Please provide the lot item custom field id or name.');
        }
        return $lotCustomField;
    }

    /**
     * @param LotItemCustField $lotCustomField
     * @return array
     */
    private function convertEntityToArray(LotItemCustField $lotCustomField): array
    {
        return [
            'Id' => $lotCustomField->Id,
            'Name' => $lotCustomField->Name,
            'Order' => $lotCustomField->Order,
            'Type' => $lotCustomField->Type,
            'InCatalog' => $lotCustomField->InCatalog,
            'SearchField' => $lotCustomField->SearchField,
            'Parameters' => $lotCustomField->Parameters,
            'Active' => $lotCustomField->Active,
            'CreatedOn' => $lotCustomField->CreatedOn,
            'CreatedBy' => $lotCustomField->CreatedBy,
            'ModifiedOn' => $lotCustomField->ModifiedOn,
            'ModifiedBy' => $lotCustomField->ModifiedBy,
            'Access' => $lotCustomField->Access,
            'Unique' => $lotCustomField->Unique,
            'Barcode' => $lotCustomField->Barcode,
            'BarcodeType' => $lotCustomField->BarcodeType,
            'BarcodeAutoPopulate' => $lotCustomField->BarcodeAutoPopulate,
            'InInvoices' => $lotCustomField->InInvoices,
            'InSettlements' => $lotCustomField->InSettlements,
            'InAdminSearch' => $lotCustomField->InAdminSearch,
            'SearchIndex' => $lotCustomField->SearchIndex,
            'InAdminCatalog' => $lotCustomField->InAdminCatalog,
            'FckEditor' => $lotCustomField->FckEditor,
            'AutoComplete' => $lotCustomField->AutoComplete,
            'LotNumAutoFill' => $lotCustomField->LotNumAutoFill,
        ];
    }

    /**
     * @param array $fields
     * @param SymfonyStyle $io
     */
    private function display(array $fields, SymfonyStyle $io): void
    {
        $rows = [];
        foreach ($fields as $fieldName => $value) {
            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y-m-d H:i:s');
            }
            $rows[] = [$fieldName, var_export($value, true)];
        }
        $io->table(
            [
                'Field',
                'Value',
            ],
            $rows
        );
    }
}
