<?php
/**
 * SAM-5796: Single CLI application for data integrity checkers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 18, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\DataIntegrityChecker\Cli\Command\Base;

use Generator;
use Sam\Core\Constants;
use Sam\Storage\ReadRepository\Entity\ReadRepositoryBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Abstract class for account level checker commands
 *
 * Class AccountRelatedCommandBase
 * @package Sam\DataIntegrityChecker\Cli
 */
abstract class AccountRelatedCheckerCommandBase extends CommandBase
{
    protected const MESSAGE_ACCOUNT_TITLE = 'Problematic items are found for account';
    protected const MESSAGE_NO_PROBLEMS = 'No problems found';

    /**
     * Configures command.
     */
    protected function configure(): void
    {
        $this->addOption('account', 'a', InputOption::VALUE_OPTIONAL, 'Filter by account ID');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $accountId = $input->getOption('account');
        $activeLotsGenerator = $this->yieldProblematicItemsWithAccountData($accountId);
        $hasProblems = false;
        while ($activeLotsGenerator->valid()) {
            $rowGenerator = $this->yieldNextAccountRow($activeLotsGenerator);
            $this->outputProblematicItems($output, $rowGenerator);
            $hasProblems = true;
        }

        if (!$hasProblems) {
            $output->writeln(sprintf('<info>%s</info>', static::MESSAGE_NO_PROBLEMS));
        }

        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param Generator $dataGenerator
     * @return Generator
     */
    protected function yieldNextAccountRow(Generator $dataGenerator): Generator
    {
        $currentAccountId = null;
        while ($dataGenerator->valid()) {
            $row = $dataGenerator->current();
            if (
                $currentAccountId
                && $currentAccountId !== (int)$row['account_id']
            ) {
                break;
            }
            $currentAccountId = (int)$row['account_id'];
            yield $row;
            $dataGenerator->next();
        }
    }

    /**
     * @param int|null $accountId
     * @return Generator
     */
    protected function yieldProblematicItemsWithAccountData(?int $accountId): Generator
    {
        return $this->prepareRepository($accountId)->yieldRows();
    }

    /**
     * @param int|null $accountId
     * @return ReadRepositoryBase
     */
    abstract protected function prepareRepository(?int $accountId): ReadRepositoryBase;

    /**
     * @param array $row
     * @return string
     */
    abstract protected function makeProblematicItemMessage(array $row): string;

    /**
     * @param OutputInterface $output
     * @param Generator $rowGenerator
     */
    private function outputProblematicItems(OutputInterface $output, Generator $rowGenerator): void
    {
        foreach ($rowGenerator as $key => $row) {
            if ($key === 0) {
                $output->writeln(
                    sprintf(
                        '<fg=red;options=bold>%s %s:</>',
                        self::MESSAGE_ACCOUNT_TITLE,
                        composeSuffix(['acc' => $row['account_id'], 'name' => $row['account_name']])
                    )
                );
            }
            $output->writeln(' * ' . $this->makeProblematicItemMessage($row));
        }
    }
}
