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
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Data\Normalize\CliNormalizer;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Storage\ReadRepository\Entity\LotItemCustField\LotItemCustFieldReadRepositoryCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\View\Admin\Form\LotCustomFieldEditForm\Dto\LotCustomFieldEditFormDtoBuilder;
use Sam\View\Admin\Form\LotCustomFieldEditForm\Save\LotCustomFieldEditFormProducer;
use Sam\View\Admin\Form\LotCustomFieldEditForm\Validate\LotCustomFieldEditFormValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cli command handler for configuring settings of lot item custom field
 *
 * Class SetLotCustomFieldCommand
 * @package Sam\CustomField\Lot\Cli\Command
 */
class SetLotCustomFieldCommand extends Command
{
    use LotItemCustFieldReadRepositoryCreateTrait;
    use UserLoaderAwareTrait;

    public const NAME = 'set';
    public const NEW_LOT_ID = 'new';

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
        // Required
        $this->addOption('id', '', InputOption::VALUE_REQUIRED, 'Specify id for editing or "' . self::NEW_LOT_ID . '" for creating');

        // Optionals
        $this->addOption('name', '', InputOption::VALUE_OPTIONAL, '<string>');
        $this->addOption('type', '', InputOption::VALUE_OPTIONAL, '<int>');
        $this->addOption('order', '', InputOption::VALUE_OPTIONAL, '<int>');
        $this->addOption('access', '', InputOption::VALUE_OPTIONAL, '<string>');
        $this->addOption('autoComplete', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('barcode', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('barcodeType', '', InputOption::VALUE_OPTIONAL, '<string>');
        $this->addOption('barcodeAutoPopulate', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('fckEditor', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('inAdminCatalog', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('inAdminSearch', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('inCatalog', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('inInvoices', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('inSettlements', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('lotCategories', '', InputOption::VALUE_OPTIONAL, '<int[]>');
        $this->addOption('lotNumAutoFill', '', InputOption::VALUE_OPTIONAL, '<string>');
        $this->addOption('parameters', '', InputOption::VALUE_OPTIONAL, '<string>');
        $this->addOption('searchField', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('searchIndex', '', InputOption::VALUE_OPTIONAL, '<bool>');
        $this->addOption('unique', '', InputOption::VALUE_OPTIONAL, '<bool>');

        $this->setDescription('This command sets settings of lot item custom field');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lotCustomField = $this->loadLotItemCustomField($input);

        $normalizer = CliNormalizer::new();
        $dto = LotCustomFieldEditFormDtoBuilder::new()->fromCli($lotCustomField, $input);

        $validator = LotCustomFieldEditFormValidator::new()->construct($normalizer);
        if ($validator->validate($dto)) {
            $producer = LotCustomFieldEditFormProducer::new()->construct($normalizer);
            $lotCustomField = $producer->save($lotCustomField, $dto, $this->getUserLoader()->loadSystemUserId());
            $output->writeln(
                '<info>Lot item custom field saved successfully!'
                . composeSuffix(array_merge(['id' => $lotCustomField->Id], $producer->getModificationInfo()))
                . '</info>'
            );
        } else {
            $this->displayErrors($validator->errorStatuses(), $output);
        }
        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param ResultStatus[] $errors
     * @param OutputInterface $output
     */
    private function displayErrors(array $errors, OutputInterface $output): void
    {
        $output->write('<error>');
        $output->write('An error has occurred while saving LotCustomField');
        $output->writeln('</error>');
        foreach ($errors as $error) {
            $output->writeln(sprintf('  * %s: %s', $error->getPayload()['property'], $error->getMessage()));
        }
    }

    /**
     * @param InputInterface $input
     * @return LotItemCustField|null
     */
    private function loadLotItemCustomField(InputInterface $input): ?LotItemCustField
    {
        $id = $input->getOption('id');
        if ($id === self::NEW_LOT_ID) {
            return null;
        }

        $lotCustomField = $this->createLotItemCustFieldReadRepository()
            ->filterId($id)
            ->loadEntity();
        if (!$lotCustomField) {
            throw new RuntimeException(sprintf('Lot custom field with id "%s" not found.', $id));
        }
        return $lotCustomField;
    }
}
