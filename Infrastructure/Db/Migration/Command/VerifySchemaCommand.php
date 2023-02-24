<?php

declare(strict_types=1);

namespace Sam\Infrastructure\Db\Migration\Command;

use Doctrine\DBAL\Exception;
use Doctrine\Migrations\Tools\Console\Command\DoctrineCommand;
use Sam\Infrastructure\Db\Migration\DbMigrationConstants;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function count;

/**
 * The VerifySchemaCommand class is responsible make verification of the schema
 * that was migrated successfully and is matching the one targeted for use by the code of SAM
 */
final class VerifySchemaCommand extends DoctrineCommand
{
    /** @var string|null */
    protected static $defaultName = 'migrations:verify-schema';
    public const COMMAND_ALIAS = 'verify-schema';

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setAliases([self::COMMAND_ALIAS])
            ->setDescription('Verifies current schema is matching the needed one from code.')
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> verify the schema was migrated successfully and is matching the one targeted for use by the code of SAM:

    <info>%command.full_name%</info>

EOT
            );
    }

    /**
     * @throws Exception
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $dependencyForDiffCommand = $this->getDependencyFactory();

        $fromSchema = $dependencyForDiffCommand->getSchemaProvider()->createSchema();
        $toSchema = $dependencyForDiffCommand->getConnection()->createSchemaManager()->createSchema();

        if ($fromSchema->hasTable(DbMigrationConstants::DB_MIG_VERSIONS_TABLE)) {
            $fromSchema->dropTable(DbMigrationConstants::DB_MIG_VERSIONS_TABLE);
        }
        if ($toSchema->hasTable(DbMigrationConstants::DB_MIG_VERSIONS_TABLE)) {
            $toSchema->dropTable(DbMigrationConstants::DB_MIG_VERSIONS_TABLE);
        }

        $diffSqls = $dependencyForDiffCommand->getSchemaDiffProvider()->getSqlDiffToMigrate($fromSchema, $toSchema);
        if (count($diffSqls) > 0) {
            $this->io->title("<error>These are the differences found in your schema to the one we need:</error>");
            foreach ($diffSqls as $sql) {
                $this->io->section($sql . ';');
            }
            $this->io->error([
                "Above are the mismatches found in the schema.",
            ]);
            return 1;
        } else {
            $this->io->success([
                "Your Db Schema is verified!"
            ]);
        }
        return 0;
    }

}
