<?php
/**
 * SAM-5843: System Parameters management by CLI script
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
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

namespace Sam\Settings\Dev\Cli\Command;

use Sam\Settings\Dev\Mapping\SettingsMappingClassGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

/**
 * Class BuildSettingConstantClassCommand
 * @package Sam\Settings\Dev\Cli
 */
class BuildSettingConstantClassCommand extends CommandBase
{
    public const NAME = 'build-constant';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption('filename', 'f', InputOption::VALUE_OPTIONAL, 'File name in order to save generated code');
        $this->setDescription('This command generates settings mapping class');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $classContent = SettingsMappingClassGenerator::new()->generate();
        $fileName = $input->getOption('filename');
        if (!$fileName) {
            $fileName = path()->classes() . '/Sam/Core/Constants/Setting.php';
        }
        file_put_contents($fileName, $classContent);
        $output->writeln('<info>Class has been generated and saved</info>');
        return Constants\Cli::EXIT_SUCCESS;
    }
}
