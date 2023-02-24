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
 * Responsible make verification of the schema against v.3.6
 */
final class VerifyVersionSchema36Command extends DoctrineCommand
{
    /** @var string|null */
    protected static $defaultName = 'migrations:verify-version-schema-36';
    public const SCHEMA_FILE = 'sam_v3.6.sql';
    public const COMMAND_ALIAS = 'verify-version-schema-36';

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setAliases([self::COMMAND_ALIAS])
            ->setDescription('Verifies current schema is matching v3.6 schema.')
            ->setHelp(
                <<<EOT
The <info>%command.name%</info> verify the schema is actually v3.6 db schema:

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
            $this->io->title("<error>These are the differences found in your schema to the one we need (v3.6):</error>");
            foreach ($diffSqls as $sql) {
                $this->io->section($sql . ';');
            }
            $this->io->error([
                "Above are the mismatches found in the schema.",
            ]);
            return 1;
        } else {
            $this->io->success([
                "Your Db Schema is verified to be matching latest v3.6 !"
            ]);
        }
        return 0;
    }

}
