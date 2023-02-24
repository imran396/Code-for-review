<?php
/**
 * SAM-7949: Predictive upcoming auction stats script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Stat\UpcomingAuction\Cli\Command;

use Sam\Stat\UpcomingAuction\Cli\Command\Internal\UpcomingAuctionActivityDtoSerializerCreateTrait;
use Sam\Stat\UpcomingAuction\Load\UpcomingAuctionActivityLoaderCreateTrait;
use Sam\Stat\UpcomingAuction\Load\UpcomingAuctionActivityQueryBuilderCreateTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GetUpcomingAuctionStatCommand
 * @package Sam\Stat\UpcomingAuction\Cli\Command
 */
class GetUpcomingAuctionStatCommand extends Command
{
    use UpcomingAuctionActivityDtoSerializerCreateTrait;
    use UpcomingAuctionActivityLoaderCreateTrait;
    use UpcomingAuctionActivityQueryBuilderCreateTrait;

    public const NAME = 'stat:upcoming-auction:get';

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct(static::NAME);
    }

    /**
     * @inheritDoc
     */

    protected function configure(): void
    {
        $this->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'Output format json or csv', 'csv');
        $this->addOption('delimiter', 'd', InputOption::VALUE_REQUIRED, 'Csv delimiter character, default comma', ',');
        $this->addOption('days', 'D', InputOption::VALUE_REQUIRED, 'Number of days out. Default 7', 7);
        $this->addOption('enclosure', 'e', InputOption::VALUE_REQUIRED, 'Csv enclosure character, double quote', '"');
        $this->addOption('escape', 'E', InputOption::VALUE_REQUIRED, 'Csv escape character', '\\');
        $this->setDescription('Get upcoming auctions activity statistic');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $activities = $this->createUpcomingAuctionActivityLoader()->load((int)$input->getOption('days'));
        if ($output->isVerbose()) {
            $query = $this->createUpcomingAuctionActivityQueryBuilder()
                ->construct()
                ->buildQuery((int)$input->getOption('days'), true);
            $output->writeln($query);
        }
        if ($input->getOption('format') === 'csv') {
            $outputString = $this->createUpcomingAuctionActivityDtoSerializer()->serializeToCsv(
                $activities,
                $input->getOption('delimiter'),
                $input->getOption('enclosure'),
                $input->getOption('escape')
            );
        } else {
            $outputString = $this->createUpcomingAuctionActivityDtoSerializer()->serializeToJson($activities);
        }
        $output->writeln($outputString);
        return 0;
    }
}

