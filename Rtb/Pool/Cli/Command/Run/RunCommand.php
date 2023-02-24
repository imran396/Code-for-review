<?php
/**
 * SAM-3611: Scaling by providing a pool of RTBDs for multiple auctions
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           10/13/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Pool\Cli\Command\Run;

use Sam\Rtb\Pool\Cli\Command\Base\CommandBase;
use Sam\Rtb\Pool\Cli\ApplicationConstants;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class RunCommand
 * @package
 */
class RunCommand extends CommandBase
{
    use RtbdPoolConfigManagerAwareTrait;

    /**
     * @var string
     */
    protected static $defaultName = ApplicationConstants::C_RUN;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->askRtbdNameIfMissed($input, $output);
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rtbdName = $input->getArgument(RunConstants::A_RTBD);

        $handler = RunHandler::new()
            ->setOutput($output)
            ->setRtbdName($rtbdName);
        $handler->handle();
        return $handler->getResultCode();
    }

    /**
     * Interactively ask for rtbd name argument
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function askRtbdNameIfMissed(InputInterface $input, OutputInterface $output): void
    {
        $rtbdName = $input->getArgument(RunConstants::A_RTBD);
        $rtbdNames = $this->getRtbdPoolConfigManager()->getRtbdNames();
        if (!in_array($rtbdName, $rtbdNames, true)) {
            $question = sprintf('Unknown rtbd instance "%s". Available names:', $rtbdName);
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion($question, $rtbdNames);
            $rtbdName = $helper->ask($input, $output, $question);
            $output->writeln('Selected: ' . $rtbdName);
            $input->setArgument(RunConstants::A_RTBD, $rtbdName);
        }
    }
}
