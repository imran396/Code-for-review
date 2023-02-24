<?php
/**
 * SAM-9363: Write repository generator
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Cli\Command;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Code\WriteRepositoryCodeGeneratorCreateTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cli command handler for generating write entity repositories
 *
 * Class BuildCommand
 * @package Sam\Infrastructure\Generate\EntityGenerator\WriteRepository\Cli\Command
 */
class BuildWriteRepositoryCommand extends Command
{
    use WriteRepositoryCodeGeneratorCreateTrait;

    public const NAME = 'build-write-repository';

    public function __construct()
    {
        parent::__construct(static::NAME);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption('entity', null, InputOption::VALUE_REQUIRED, 'Generate write repository for entity list');
        $this->addOption('all', null, InputOption::VALUE_NONE, 'Generate all write repositories');
        $this->setDescription('Generate write repository');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $writeRepositoryCodeGenerator = $this->createWriteRepositoryCodeGenerator();
        if ($input->getOption('all')) {
            $entityList = $writeRepositoryCodeGenerator->generateAll();
        } else {
            $entityOption = (string)$input->getOption('entity');
            $entityList = $this->normalizeEntityOption($entityOption);
            if (!$entityList) {
                throw new InvalidArgumentException('Provide at least one entity');
            }
            array_walk($entityList, [$writeRepositoryCodeGenerator, 'generate']);
        }

        foreach ($entityList as $entity) {
            $output->writeln('Successfully generated write repository class for entity ' . $entity);
        }
        return Constants\Cli::EXIT_SUCCESS;
    }

    protected function normalizeEntityOption(string $entityOption): array
    {
        $entityList = explode(',', $entityOption);
        $entityList = array_map(
            static function (string $entityOrTable) {
                return TextTransformer::new()->toCamelCase(trim($entityOrTable));
            },
            $entityList
        );
        $entityList = array_filter($entityList);
        return $entityList;
    }
}
