<?php
/**
 * SAM-8867: Modularize JS constants generation script
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Js\Constant\Generate\Cli\Command\Generate;

use Sam\Core\Constants;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConstantsCommand extends Command
{
    public const NAME = 'generate-constants-run';

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
        $this->setDescription('Generate js constants from php constants');
        $this->addOption('j', null, InputOption::VALUE_OPTIONAL, 'Javascript constants path');
        $this->addOption('p', null, InputOption::VALUE_OPTIONAL, 'Php constants path');
        $this->addOption('n', null, InputOption::VALUE_OPTIONAL, 'Namespace of PHP classes');
        $this->setHelp('Usage: --j=<path to js constants> --p=<path to php constants> --n=<namespace of PHP classes> \Sam\Core\Constants');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $jsConstantsPath = $input->getOption('j') ?: path()->sysRoot() . "/assets/js/src/Constants";

        $customPhpConstantsPath = $input->getOption('p');
        if ($customPhpConstantsPath) {
            $phpConstantsPath = [$customPhpConstantsPath];
        } else {
            $phpConstantsPath = [];
            foreach (Config::ALLOWED_NAMESPACES as $namespace) {
                $phpConstantsPath[] = path()->sysRoot() . "/includes/classes/" . str_replace('\\', '/', $namespace);
            }
        }


        $output->writeln('Folders:');
        $output->writeln('Path to js constants: ' . $jsConstantsPath);
        $output->writeln('Cleaning up...');
        ConstantsCleaner::new()->clean($jsConstantsPath);

        $output->writeln('Processing: ');

        foreach ($phpConstantsPath as $path) {
            $output->writeln('Path to php constants: ' . $path);
            ConstantsMaker::new()->setOutput($output)
                ->handle($jsConstantsPath, $path);
        }

        $message = 'Done';
        $output->writeln($message);
        return Constants\Cli::EXIT_SUCCESS;
    }
}
