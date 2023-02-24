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

namespace Sam\Rtb\Pool\Cli\Command\Link;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Pool\Auction\Save\AuctionRtbdUpdaterAwareTrait;
use Sam\Rtb\Pool\Auction\Validate\AuctionRtbdCheckerCreateTrait;
use Sam\Rtb\Pool\Cli\ApplicationConstants;
use Sam\Rtb\Pool\Cli\Command\Base\CommandBase;
use Sam\Rtb\Pool\Config\RtbdPoolConfigManagerAwareTrait;
use Sam\Rtb\Pool\Discovery\Strategy\Manual\ManualRtbdListProducer;
use Sam\Storage\ReadRepository\Entity\Auction\AuctionReadRepositoryCreateTrait;
use Sam\User\Load\UserLoader;
use Sam\User\Load\UserLoaderAwareTrait;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class LinkCommand
 * @package
 */
class LinkCommand extends CommandBase
{
    use AuctionReadRepositoryCreateTrait;
    use AuctionRtbdCheckerCreateTrait;
    use AuctionRtbdUpdaterAwareTrait;
    use ConfigRepositoryAwareTrait;
    use RtbdPoolConfigManagerAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var string
     */
    protected static $defaultName = ApplicationConstants::C_LINK;

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
        $rtbdName = $input->getArgument(LinkConstants::A_RTBD);
        $auctionOpt = $input->getOption(LinkConstants::O_AUCTION);
        $editorOpt = $input->getOption(LinkConstants::O_EDITOR);
        $auctionIds = [];
        $errorMessages = [];

        if ($auctionOpt) {
            $auctionIds = explode(',', $auctionOpt);
            $auctionIds = ArrayCast::castInt($auctionIds, Constants\Type::F_INT_POSITIVE);
            $auctionIds = array_filter($auctionIds);
            if (!$auctionIds) {
                $errorMessages[] = 'Incorrect value for option'
                    . composeSuffix(['option' => $auctionOpt, 'value' => $auctionOpt]);
            } else {
                // Check auctions availability
                $rows = $this->createAuctionReadRepository()
                    ->filterAuctionStatusId(Constants\Auction::$openAuctionStatuses)
                    ->filterId($auctionIds)
                    ->joinAccountFilterActive(true)
                    ->select(['a.id'])
                    ->loadRows();
                $availableAuctionIds = ArrayCast::arrayColumnInt($rows, 'id');
                $missedAuctionIds = array_diff($auctionIds, $availableAuctionIds);
                if ($missedAuctionIds) {
                    $errorMessages[] = 'Auction unavailable' . composeSuffix(['a' => array_values($missedAuctionIds)]);
                }
            }
        } else {
            $errorMessages[] = sprintf('Required option missed "--%s"', LinkConstants::O_AUCTION);
        }

        $editorUsername = $editorOpt ?: $this->cfg()->get(UserLoader::CFG_SYSTEM_USERNAME);
        $editorUser = $this->getUserLoader()->loadByUsername($editorUsername, true);
        if (!$editorUser) {
            $errorMessages[] = sprintf('User cannot be found by username "%s"', $editorUsername);
        }

        if (!$errorMessages) {
            foreach ($auctionIds as $auctionId) {
                // Check rtbd instance linking constraints
                $actualAuctionDescriptors = ManualRtbdListProducer::new()
                    ->setAuctionId($auctionId)
                    ->makePrioritizedList();
                $actualDescriptor = $this->getRtbdPoolConfigManager()->findDescriptorByName($rtbdName, $actualAuctionDescriptors);
                if (!$actualDescriptor) {
                    $errorMessages[] = 'Auction does not correspond rtbd instance constraints'
                        . composeSuffix(['a' => $auctionId, 'rtbd' => $rtbdName]);
                }
            }
        }

        if ($errorMessages) {
            throw new InvalidOptionException(implode("\n", $errorMessages));
        }

        LinkHandler::new()
            ->setAuctionIds($auctionIds)
            ->setEditorUser($editorUser)
            ->setOutput($output)
            ->setRtbdName($rtbdName)
            ->handle();

        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * Interactively ask for rtbd name argument
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function askRtbdNameIfMissed(InputInterface $input, OutputInterface $output): void
    {
        $rtbdName = $input->getArgument(LinkConstants::A_RTBD);
        $rtbdNames = $this->getRtbdPoolConfigManager()->getRtbdNames();
        if (!in_array($rtbdName, $rtbdNames, true)) {
            $question = sprintf('Unknown rtbd instance "%s". Available names:', $rtbdName);
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion($question, $rtbdNames);
            $rtbdName = $helper->ask($input, $output, $question);
            $output->writeln('Selected: ' . $rtbdName);
            $input->setArgument(LinkConstants::A_RTBD, $rtbdName);
        }
    }
}
