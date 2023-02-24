<?php
/**
 * Update command handler, it refreshes auction bindings to rtbd instances in pool
 *
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

namespace Sam\Rtb\Pool\Cli\Command\Update;

use Sam\Core\Data\TypeCast\ArrayCast;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Rtb\Pool\Cli\Command\Base\CommandBase;
use Sam\Rtb\Pool\Cli\ApplicationConstants;
use Sam\User\Load\UserLoader;
use Sam\User\Load\UserLoaderAwareTrait;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

/**
 * Class UpdateCommand
 * @package
 */
class UpdateCommand extends CommandBase
{
    use ConfigRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * @var string
     */
    protected static $defaultName = ApplicationConstants::C_UPDATE;

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int 0 if everything went fine, or an error code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $isAll = $input->getOption(UpdateConstants::O_ALL);
        $editorOpt = (string)$input->getOption(UpdateConstants::O_EDITOR);
        $shouldUpdateLinked = (bool)$input->getOption(UpdateConstants::O_LINKED);
        $filterAccountIds = null;
        $filterAuctionIds = null;
        $filterAuctionStatusIds = null;
        if (!$isAll) {
            $accountOpt = $input->getOption(UpdateConstants::O_ACCOUNT);
            if ($accountOpt) {
                $filterAccountIds = explode(',', $accountOpt);
                $filterAccountIds = ArrayCast::castInt($filterAccountIds, Constants\Type::F_INT_POSITIVE);
                $filterAccountIds = array_filter($filterAccountIds);
                if (!$filterAccountIds) {
                    throw new InvalidOptionException(sprintf('Incorrect value for option "--%s"', UpdateConstants::O_ACCOUNT));
                }
            }

            $auctionOpt = $input->getOption(UpdateConstants::O_AUCTION);
            if ($auctionOpt) {
                $filterAuctionIds = explode(',', $auctionOpt);
                $filterAuctionIds = ArrayCast::castInt($filterAuctionIds, Constants\Type::F_INT_POSITIVE);
                $filterAuctionIds = array_filter($filterAuctionIds);
                if (!$filterAuctionIds) {
                    throw new InvalidOptionException(sprintf('Incorrect value for option "--%s"', UpdateConstants::O_AUCTION));
                }
            }

            $auctionStatusOpt = $input->getOption(UpdateConstants::O_AUCTION_STATUS);
            if ($auctionStatusOpt) {
                $filterAuctionStatusIds = explode(',', $auctionStatusOpt);
                $filterAuctionStatusIds = ArrayCast::castInt($filterAuctionStatusIds, Constants\Auction::$auctionStatuses);
                $filterAuctionStatusIds = array_filter($filterAuctionStatusIds);
                if (!$filterAuctionStatusIds) {
                    throw new InvalidOptionException(sprintf('Incorrect value for option "--%s"', UpdateConstants::O_AUCTION_STATUS));
                }
            }
        }

        $editorUsername = $editorOpt ?: $this->cfg()->get(UserLoader::CFG_SYSTEM_USERNAME);
        $editorUser = $this->getUserLoader()->loadByUsername($editorUsername, true);
        if (!$editorUser) {
            $errorMessages[] = sprintf('User cannot be found by username "%s"', $editorUsername);
        }

        if (
            !$filterAccountIds
            && !$filterAuctionIds
            && !$isAll
        ) {
            throw new InvalidOptionException(
                'Incorrect options. You should define filtering by '
                . implode(
                    ', or ',
                    [
                        '--' . UpdateConstants::O_ACCOUNT,
                        '--' . UpdateConstants::O_AUCTION,
                        '--' . UpdateConstants::O_ALL,
                    ]
                )
            );
        }

        $updateHandler = UpdateHandler::new()
            ->enableUpdateLinked($shouldUpdateLinked)
            ->setEditorUser($editorUser)
            ->setOutput($output);
        if ($filterAccountIds) {
            $updateHandler->filterAccountId($filterAccountIds);
        }
        if ($filterAuctionIds) {
            $updateHandler->filterAuctionId($filterAuctionIds);
        }
        if ($filterAuctionStatusIds) {
            $updateHandler->filterAuctionStatusId($filterAuctionStatusIds);
        }
        $updateHandler->handle();

        return Constants\Cli::EXIT_SUCCESS;
    }
}
