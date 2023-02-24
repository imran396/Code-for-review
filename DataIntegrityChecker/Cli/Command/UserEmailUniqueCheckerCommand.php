<?php
/**
 * SAM-10728: Supply uniqueness for user fields: Implement data integrity checkers for user fields
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 8, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\DataIntegrityChecker\Cli\Command;

use Generator;
use Sam\Core\Constants;
use Sam\DataIntegrityChecker\Cli\Command\Base\CommandBase;
use Sam\User\Validate\UserDataIntegrityCheckerAwareTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command calls a checker that detects users with non-unique emails
 *
 * Class UserEmailUniqueCheckerCommand
 * @package Sam\DataIntegrityChecker\Cli
 */
class UserEmailUniqueCheckerCommand extends CommandBase
{
    use UserDataIntegrityCheckerAwareTrait;

    public const NAME = 'UserEmailUnique';

    private const MESSAGE_DUPLICATES = 'Email \'%s\' is duplicated %s times for user ids (%s)';
    private const MESSAGE_NO_DUPLICATES = 'There are no duplicated emails';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Shows users with non-unique emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $customFieldsDuplicateRows = $this->yieldCustomFieldDuplicateData();
        $hasProblems = false;
        foreach ($customFieldsDuplicateRows as $row) {
            $output->writeln(vsprintf(self::MESSAGE_DUPLICATES, (array)$row));
            $hasProblems = true;
        }

        if (!$hasProblems) {
            $output->writeln(sprintf('<info>%s</info>', self::MESSAGE_NO_DUPLICATES));
        }

        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @return Generator
     */
    private function yieldCustomFieldDuplicateData(): Generator
    {
        return $this->getUserDataIntegrityChecker()
            ->prepareEmailDuplicateSearch()
            ->yieldRows();
    }
}
