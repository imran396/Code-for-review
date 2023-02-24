<?php
/**
 * SAM-9891: Get rid of RepositoryBase::delete() usage
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Cli\Command;

use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Transform\Text\TextTransformer;
use Sam\Infrastructure\Generate\EntityGenerator\DeleteRepository\Code\DeleteRepositoryCodeGeneratorCreateTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cli command handler for generating delete entity repositories
 *
 * Class BuildReadRepositoryCommand
 * @package Sam\Infrastructure\Generate\EntityGenerator\ReadRepository\Cli\Command
 */
class BuildDeleteRepositoryCommand extends Command
{
    use DeleteRepositoryCodeGeneratorCreateTrait;

    public const NAME = 'build-delete-repository';

    public function __construct()
    {
        parent::__construct(static::NAME);
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->addOption('entity', null, InputOption::VALUE_REQUIRED, 'Generate delete repository for entity list');
        $this->addOption('all', null, InputOption::VALUE_NONE, 'Generate all delete repositories');
        $this->setDescription('Generate delete repository');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $deleteRepositoryCodeGenerator = $this->createDeleteRepositoryCodeGenerator();
        if ($input->getOption('all')) {
            $entityList = $deleteRepositoryCodeGenerator->generateAll();
        } else {
            $entityOption = (string)$input->getOption('entity');
            $entityList = $this->normalizeEntityOption($entityOption);
            if (!$entityList) {
                throw new InvalidArgumentException('Provide at least one entity');
            }
            array_walk($entityList, [$deleteRepositoryCodeGenerator, 'generate']);
        }

        foreach ($entityList as $entity) {
            $output->writeln('Successfully generated delete repository class for entity ' . $entity);
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
