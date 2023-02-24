<?php
/**
 * SAM-6292: Move fields from auction_cache to auction_details_cache
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug. 31, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Auction\Cache\Cli\Command;


use InvalidArgumentException;
use Sam\Auction\Cache\AuctionDetailsDbCacheManagerCreateTrait;
use Sam\Core\Constants;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\User\Load\UserLoader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command handling a request to update the auction details cache
 *
 * Class UpdateAuctionDetailsCacheCommand
 * @package Sam\Auction\Cache\Cli
 */
class UpdateAuctionDetailsCacheCommand extends Command
{
    use AuctionDetailsDbCacheManagerCreateTrait;
    use ConfigRepositoryAwareTrait;

    public const NAME = 'update-cache:auction-details';

    /**
     * @inheritDoc
     */
    public function __construct(?string $name = null)
    {
        parent::__construct($name ?? static::NAME);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption('account', null, InputOption::VALUE_REQUIRED, 'Specify Account id filtering separated by comma');
        $this->addOption('auction', null, InputOption::VALUE_REQUIRED, 'Specify Auction id filtering separated by comma');
        $this->addOption('force', null, InputOption::VALUE_NONE, 'Update actual fields, not only invalidated');
        $this->addOption('maxExecTime', null, InputOption::VALUE_REQUIRED, 'Process max execution time. "0" for unlimited');
        $this->setDescription('Refresh auction details cache');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $execStartTime = time();
        $maxExecTime = $input->getOption('maxExecTime') ?? $this->cfg()->get('core->auction->details->dbCacheUpdateMaxExecutionTime');
        $accountOptionValue = $input->getOption('account');
        $auctionOptionValue = $input->getOption('auction');
        if ($accountOptionValue && $auctionOptionValue) {
            throw new InvalidArgumentException('Please specify only one of --account or --auction options');
        }

        $force = $input->getOption('force');
        $cacheManager = $this->createAuctionDetailsDbCacheManager();
        if ($auctionOptionValue) {
            $auctionIds = $this->extractIdsFromOptionValue($auctionOptionValue);
        } else {
            $accountIds = $accountOptionValue ? $this->extractIdsFromOptionValue($accountOptionValue) : [];
            $auctionIds = $cacheManager->detectExpectedRefreshCacheAuctionIds($force, $accountIds);
        }
        $editorUserId = UserLoader::new()->loadSystemUserId();
        $resultGenerator = $cacheManager->yieldUpdating($auctionIds, $editorUserId, $force);
        $output->writeln('<info>Items processed: </info>');
        $progressBar = new ProgressBar($output);
        $progressBar->start();
        while ($resultGenerator->valid()) {
            $progressBar->advance();
            $resultGenerator->next();
            //If we run from cron script, then we have execution time limitation
            if ($maxExecTime > 0 && time() > ($execStartTime + $maxExecTime)) {
                $message = 'UpdateAuctionDetailsCache command completed due to execution time limit';
                log_debug(
                    $message . composeSuffix(
                        ['progress' => $progressBar->getProgress(), 'maxExecTime' => $maxExecTime]
                    )
                );
                $progressBar->finish();
                $output->writeln("\n<comment>{$message}</comment>");
                break;
            }
        }
        $progressBar->finish();
        $output->writeln('');
        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param string $optionValue
     * @return array
     */
    private function extractIdsFromOptionValue(string $optionValue): array
    {
        $ids = explode(',', $optionValue);
        $ids = array_map('trim', $ids);
        return $ids;
    }
}
