<?php
/**
 * SAM-5009: Reorganize file assets that change at runtime
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Oleg Kovalyov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Validate\Cli\Command\Translation;

use Sam\Core\Path\PathResolver;
use Sam\CustomField\Auction\Load\AuctionCustomFieldLoaderAwareTrait;
use Sam\CustomField\Auction\Translate\AuctionCustomFieldTranslationManagerAwareTrait;
use Sam\CustomField\Lot\Load\LotCustomFieldLoaderCreateTrait;
use Sam\CustomField\Lot\Translate\LotCustomFieldTranslationManagerAwareTrait;
use Sam\CustomField\User\Load\UserCustomFieldLoaderAwareTrait;
use Sam\CustomField\User\Translate\UserCustomFieldTranslationManagerAwareTrait;
use Sam\File\Manage\FileException;
use Sam\File\Manage\FileManagerCreateTrait;
use Sam\Installation\Validate\Cli\Command\Base\CommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

class TranslationCommand extends CommandBase
{
    use AuctionCustomFieldLoaderAwareTrait;
    use AuctionCustomFieldTranslationManagerAwareTrait;
    use FileManagerCreateTrait;
    use LotCustomFieldLoaderCreateTrait;
    use LotCustomFieldTranslationManagerAwareTrait;
    use UserCustomFieldLoaderAwareTrait;
    use UserCustomFieldTranslationManagerAwareTrait;

    /**
     * @var string
     */
    public const NAME = 'translation';

    /**
     * Configures command.
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption('fix', null, InputOption::VALUE_NONE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $needFixTranslations = $input->getOption('fix');
        if ($needFixTranslations) {
            $this->generateCustomFieldTranslations($output);
        } else {
            $this->checkCustomFieldTranslations($output);
        }
        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    private function checkCustomFieldTranslations(OutputInterface $output): void
    {
        $hasError = false;
        $translationsMasterPath = substr(path()->customFieldTranslationMaster(), strlen(path()->sysRoot()));
        foreach (Constants\CustomField::$masterTranslationFiles as $requiredFile) {
            $filePath = $translationsMasterPath . '/' . $requiredFile;
            try {
                if (!$this->createFileManager()->exist($filePath)) {
                    $hasError = true;
                    $output->writeln("<error>$requiredFile not found.</error>");
                }
            } catch (FileException) {
                log_warning('Failed to read file ' . $filePath);
                $hasError = true;
            }
        }

        if (!$hasError) {
            $output->writeln('<info>All master translation files are found</info>');
        }
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    private function generateCustomFieldTranslations(OutputInterface $output): void
    {
        // Generate master/auctioncustomfields.csv
        $customFields = $this->getAuctionCustomFieldLoader()->loadAll();
        $filePath = $this->makeRelativePath(Constants\CustomField::AUCTION_CUSTOM_FIELD_TRANSLATION_FILE);
        $this->createTranslationMasterFileIfNotExist($customFields, $filePath);
        foreach ($customFields as $customField) {
            $this->getAuctionCustomFieldTranslationManager()->refresh($customField);
        }

        // Generate master/usercustomfields.csv
        $customFields = $this->getUserCustomFieldLoader()->loadAll();
        $filePath = $this->makeRelativePath(Constants\CustomField::USER_CUSTOM_FIELD_TRANSLATION_FILE);
        $this->createTranslationMasterFileIfNotExist($customFields, $filePath);
        foreach ($customFields as $customField) {
            $this->getUserCustomFieldTranslationManager()->refresh($customField);
        }

        // Generate master/customfields.csv
        $customFields = $this->createLotCustomFieldLoader()->loadAll();
        $filePath = $this->makeRelativePath(Constants\CustomField::LOT_CUSTOM_FIELD_TRANSLATION_FILE);
        $this->createTranslationMasterFileIfNotExist($customFields, $filePath);
        foreach ($customFields as $customField) {
            $this->getLotCustomFieldTranslationManager()->refresh($customField);
        }

        $fileList = implode(', ', Constants\CustomField::$masterTranslationFiles);
        $output->writeln("<info>master files {$fileList} were successfully generated.</info>");
    }

    /**
     * @param array $customFields
     * @param string $filePath
     */
    private function createTranslationMasterFileIfNotExist(array $customFields, string $filePath): void
    {
        try {
            if (
                !count($customFields)
                || !$this->createFileManager()->exist($filePath)
            ) {
                $this->createFileManager()->write('', $filePath);
            }
        } catch (FileException) {
            log_warning('Failed to create translation master file ' . $filePath);
        }
    }

    private function makeRelativePath(string $fileName): string
    {
        return PathResolver::CUSTOM_FIELD_TRANSLATION_MASTER . '/' . $fileName;
    }
}
