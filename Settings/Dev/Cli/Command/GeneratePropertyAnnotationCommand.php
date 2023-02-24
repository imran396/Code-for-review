<?php
/**
 * SAM-5843: System Parameters management by CLI script
 * SAM-4720: Refactor logic of System Parameters management pages to editor modules
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 20, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Dev\Cli\Command;

use Sam\Core\Constants;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GeneratePropertyAnnotationCommand
 * @package Sam\Settings\Dev\Cli
 */
class GeneratePropertyAnnotationCommand extends CommandBase
{
    public const NAME = 'generate:property-annotation';

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setDescription('This command generates and outputs settings properties annotation to use in DTO');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $annotation = $this->generateAnnotation();
        $output->writeln($annotation);
        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @return string
     */
    private function generateAnnotation(): string
    {
        $propertyAnnotations = array_map([$this, 'makePropertyAnnotation'], Constants\Setting::$typeMap);
        return '/**' . PHP_EOL . implode(PHP_EOL, $propertyAnnotations) . PHP_EOL . '**/';
    }

    /**
     * @param array $config
     * @return string
     */
    private function makePropertyAnnotation(array $config): string
    {
        return sprintf('* @property %s $%s', $config['type'], $config['property']);
    }
}
