<?php
/**
 * SAM-6856: Soft-deleted Auction restore
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan. 10, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Entity\Restore\Cli\Command;

use InvalidArgumentException;
use Sam\Entity\Restore\Cli\Command\Handler\EntityRestoreCommandHandlerProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Cli command handling a soft-deleted entity restore request
 *
 * Class RunCommand
 * @package Sam\Entity\Restore\Cli
 */
class RunCommand extends Command
{
    public const NAME = 'run';

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
        $this->addOption('entity', 'e', InputOption::VALUE_REQUIRED, 'Specify entity name');
        $this->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'Specify entity id');
        $this->setDescription('Restore soft-deleted entity');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityName = (string)$input->getOption('entity');
        $entityId = (int)$input->getOption('id');
        $handler = EntityRestoreCommandHandlerProvider::new()->construct()->getHandler($entityName);
        if (!$handler) {
            throw new InvalidArgumentException("Invalid entity name '{$entityName}'. Handler not found");
        }
        $result = $handler->restore($entityId);
        $io = new SymfonyStyle($input, $output);
        if ($result->hasError()) {
            $io->error($result->errorMessages());
        }
        if ($result->hasSuccess()) {
            $io->success($result->successMessages());
        }
        if ($result->hasWarning()) {
            $io->warning($result->warningMessages());
        }
        if (
            $result->hasInfo()
            && $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE
        ) {
            $io->block($result->infoMessages(), 'Info');
        }
        return 0;
    }
}
