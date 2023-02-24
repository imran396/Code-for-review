<?php
/**
 * SAM-5796: Single CLI application for data integrity checkers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 19, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\DataIntegrityChecker\Cli\Command;

use Generator;
use Sam\DataIntegrityChecker\Cli\Command\Base\CommandBase;
use Sam\Lot\Validate\LotDataIntegrityCheckerAwareTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

/**
 * Command calls a checker that detects custom fields that active more than once for one lot
 *
 * Class ActiveOneLotCustomFieldCheckerCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class ActiveOneLotCustomFieldCheckerCommand extends CommandBase
{
    use LotDataIntegrityCheckerAwareTrait;

    public const NAME = 'ActiveOneLotCustomField';

    private const MESSAGE_CUSTOM_FIELD = 'Custom field \'%s\' is active in lot %s %s times';
    private const MESSAGE_NO_CUSTOM_FIELDS = 'There are no duplicate custom fields';

    /**
     * Configures command.
     */
    protected function configure(): void
    {
        $this->setDescription('Shows custom fields active more than once for one lot');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customFieldsDuplicateRows = $this->yieldCustomFieldDuplicateData();
        $hasProblems = false;
        foreach ($customFieldsDuplicateRows as $row) {
            $output->writeln(vsprintf(self::MESSAGE_CUSTOM_FIELD, (array)$row));
            $hasProblems = true;
        }

        if (!$hasProblems) {
            $output->writeln(sprintf('<info>%s</info>', self::MESSAGE_NO_CUSTOM_FIELDS));
        }

        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @return Generator
     */
    private function yieldCustomFieldDuplicateData(): Generator
    {
        return $this->getLotDataIntegrityChecker()
            ->prepareCustomFieldDuplicateSearch()
            ->yieldRows();
    }
}
