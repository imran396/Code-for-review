<?php
/**
 * SAM-9486: Entity factory class generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Cli\Command;

use Sam\Core\Constants;
use Sam\Infrastructure\Generate\EntityGenerator\EntityFactory\Code\EntityFactoryCodeGeneratorCreateTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BuildEntityFactoryCommand
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Cli\Command
 */
class BuildEntityFactoryCommand extends Command
{
    use EntityFactoryCodeGeneratorCreateTrait;

    public const NAME = 'build-entity-factory';

    public function __construct()
    {
        parent::__construct(static::NAME);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setDescription('Generate entity factory');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createEntityFactoryCodeGenerator()->generate();
        $output->writeln('Successfully generated entity factory');
        return Constants\Cli::EXIT_SUCCESS;
    }
}
