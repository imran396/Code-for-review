<?php
/**
 * SAM-11100 Integrate Doctrine DB Migration library
 *
 * Project        sam
 * @author        Georgi Nikolov
 * @version       SVN: $Id: $
 * @since         Aug 30, 2022
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Infrastructure\Db\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\AvailableMigration;
use Doctrine\Migrations\Metadata\ExecutedMigration;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\Migrations\MigratorConfiguration;
use Doctrine\Migrations\Provider\SchemaProvider;
use Doctrine\Migrations\Provider\StubSchemaProvider;
use Doctrine\Migrations\Tools\Console\Command as DoctrineCommand;
use Doctrine\Migrations\Version\Direction;
use Doctrine\Migrations\Version\Version;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Sam\Core\Service\CustomizableClass;
use Sam\DBAL\Types\Enum\EnumType;
use Sam\Infrastructure\Db\Migration\Command as SamCommand;
use Sam\Infrastructure\Db\Migration\Command\VerifyVersionSchema36Command;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * CLI application for migrating database schema
 * Entry point: bin/doctrine-migrations.php
 */
class MigrationsCliApplication extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    protected InputInterface $input;
    protected OutputInterface $output;
    protected SymfonyStyle $io;

    private static ?MigrationsCliApplication $instance = null;
    private string $migrationsDbTable;
    private string $migrationsNamespace;
    private string $migrationsPath;
    private string $schemaPath;
    private string $temporaryDatabaseForDiff;

    public static function new(): static
    {
        if (self::$instance === null) {
            return parent::_new(self::class);
        }
        self::$instance = parent::_new(self::class);
        return self::$instance;
    }

    public function construct(
        ?string $migrationsPath = null,
        ?string $schemaPath = null,
        ?string $migrationsNamespace = null,
        ?string $temporaryDatabaseForDiff = null,
        ?string $migrationsDbTable = null
    ): static {
        $this->migrationsPath = $migrationsPath ?? path()->sysRoot() . '/' . DbMigrationConstants::DB_MIG_PATH_FROM_SYS_ROOT;
        $this->schemaPath = $schemaPath ?? path()->sysRoot() . '/' . DbMigrationConstants::DB_SCHEMA_PATH_FROM_SYS_ROOT;
        $this->migrationsNamespace = $migrationsNamespace ?? DbMigrationConstants::DB_MIG_NAMESPACE;
        $this->temporaryDatabaseForDiff =
            $temporaryDatabaseForDiff ?? $this->getTemporaryDbName();
        $this->migrationsDbTable = $migrationsDbTable ?? DbMigrationConstants::DB_MIG_VERSIONS_TABLE;
        return $this;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function run(array $inputArgs = null, OutputInterface $output = null): int
    {
        $this->configureIO($inputArgs, $output);

        // connection
        $connectionConfig = $this->prepareDbConfig();
        $connectionMainDb = DriverManager::getConnection($connectionConfig);

        // register enum type
        if (!Type::hasType('enumType')) {
            Type::addType('enumType', EnumType::class);
        }
        if (!$connectionMainDb->getDatabasePlatform()->hasDoctrineTypeMappingFor('enum')) {
            $connectionMainDb->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'enumType');
        }

        // Configuration
        $configurationMainDb = $this->buildMigrationConfiguration();

        // schema
        $schemaMainDb = $connectionMainDb->createSchemaManager()->createSchema();
        $schemaProviderForMainDb = new StubSchemaProvider($schemaMainDb);
        $dependencyFactoryMainDb = DependencyFactory::fromConnection(
            new ExistingConfiguration($configurationMainDb),
            new ExistingConnection($connectionMainDb),
        );
        $dependencyFactoryMainDb->setDefinition(SchemaProvider::class, static fn() => $schemaProviderForMainDb);

        // if we are executing the diff or verify-schema command
        if (
            str_contains((string)$this->input->getFirstArgument(), 'diff')
            || str_contains((string)$this->input->getFirstArgument(), 'verify-schema')
            || str_contains((string)$this->input->getFirstArgument(), VerifyVersionSchema36Command::COMMAND_ALIAS)
        ) {
            $sqlSchemaFileToLoad = (str_contains(
                (string)$this->input->getFirstArgument(),
                VerifyVersionSchema36Command::COMMAND_ALIAS
            )) ? VerifyVersionSchema36Command::SCHEMA_FILE : '';
            $dependencyForDiffCommand = $this->buildDependencyWithoutORM(
                $schemaMainDb,
                $connectionMainDb,
                $connectionConfig,
                $sqlSchemaFileToLoad
            );
        } else {
            $dependencyForDiffCommand = $dependencyFactoryMainDb;
        }

        $cli = new SymfonyConsoleApplication('SAM Doctrine Migrations');
        $cli->setCatchExceptions(true);
        $commands = [
            new DoctrineCommand\ExecuteCommand($dependencyFactoryMainDb),
            new DoctrineCommand\GenerateCommand($dependencyFactoryMainDb),
            new DoctrineCommand\LatestCommand($dependencyFactoryMainDb),
            new DoctrineCommand\ListCommand($dependencyFactoryMainDb),
            new DoctrineCommand\MigrateCommand($dependencyFactoryMainDb),
            new DoctrineCommand\StatusCommand($dependencyFactoryMainDb),
            new DoctrineCommand\SyncMetadataCommand($dependencyFactoryMainDb),
            new DoctrineCommand\VersionCommand($dependencyFactoryMainDb),
            new DoctrineCommand\UpToDateCommand($dependencyFactoryMainDb),
            new DoctrineCommand\DiffCommand($dependencyForDiffCommand),
            new SamCommand\VerifySchemaCommand($dependencyForDiffCommand),
            new SamCommand\VerifyVersionSchema36Command($dependencyForDiffCommand)
        ];

        $cli->addCommands($commands);
        return $cli->run($this->input, $this->output);
    }


    private function buildDependencyWithoutORM(
        Schema $schemaMainDb,
        Connection $connectionMainDb,
        array $connectionConfig,
        string $sqlSchemaFileToLoad = ''
    ): DependencyFactory {
        // Secondary Db - connection
        $connectionSecondaryDb = DriverManager::getConnection(
            array_merge($connectionConfig, ['dbname' => $this->temporaryDatabaseForDiff])
        );

        // register Enum Type
        $connectionSecondaryDb->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'enumType');

        // Configuration
        $configurationSecondaryDb = $this->buildMigrationConfiguration();
        $dependencyFactorySecondaryDb = DependencyFactory::fromConnection(
            new ExistingConfiguration($configurationSecondaryDb),
            new ExistingConnection($connectionSecondaryDb),
        );

        $this->recreateTemporaryDb($connectionMainDb);

        if ($sqlSchemaFileToLoad !== '') {
            $sqlSchemaFilePath = $this->schemaPath . '/' . $sqlSchemaFileToLoad;
            if ($this->executeSql($connectionSecondaryDb, file_get_contents($sqlSchemaFilePath))) {
                $this->io->block(["Building schema - Success."], 'step');
            } else {
                $this->io->block(["Building schema - Fail."], 'step');
            }
            $shouldBuildSchemaProvider = true;
        } else {
            $shouldBuildSchemaProvider = $this->executeMigrations($dependencyFactorySecondaryDb);
        }

        if ($shouldBuildSchemaProvider) {
            $schemaProviderForSecondaryDb = new StubSchemaProvider($schemaMainDb);
            $dependencyFactorySecondaryDb = DependencyFactory::fromConnection(
                new ExistingConfiguration($configurationSecondaryDb),
                new ExistingConnection($connectionSecondaryDb),
            );
            $dependencyFactorySecondaryDb->setDefinition(
                SchemaProvider::class,
                static fn() => $schemaProviderForSecondaryDb
            );
        }
        return $dependencyFactorySecondaryDb;
    }

    private function executeMigrations(DependencyFactory $dependencyFactorySecondaryDb): bool
    {
        $planCalculator = $dependencyFactorySecondaryDb->getMigrationPlanCalculator();
        $availableMigrations = $dependencyFactorySecondaryDb->getMigrationRepository()->getMigrations()->getItems();

        // execute migrations in the temporary DB
        if (count($availableMigrations) > 0) {
            $this->io->block(["Executing migrations. Please wait..."], 'step');

            $migrationVersionsToExecute = [];
            foreach ($availableMigrations as $availableMigration) {
                $migrationVersionsToExecute[] = $availableMigration->getVersion();
            }
            $dependencyFactorySecondaryDb->getMetadataStorage()->ensureInitialized();
            try {
                $planUp = $planCalculator->getPlanForVersions(
                    $migrationVersionsToExecute,
                    Direction::UP
                );

                $dependencyFactorySecondaryDb->getMigrator()->migrate(
                    $planUp,
                    (new MigratorConfiguration())->setAllOrNothing(false)
                );

                // check for skipped migrations, to add them explicitly to metadata storage.
                $availableVersions = array_map(static function (AvailableMigration $availableMigration): Version {
                    return $availableMigration->getVersion();
                }, $availableMigrations);

                $executedMigrations = $dependencyFactorySecondaryDb
                    ->getMetadataStorage()
                    ->getExecutedMigrations()
                    ->getItems();
                $executedVersions = array_map(static function (ExecutedMigration $executedMigration): Version {
                    return $executedMigration->getVersion();
                }, $executedMigrations);

                $skippedVersions = array_diff($availableVersions, $executedVersions);
                if (count($skippedVersions) > 0) {
                    foreach ($skippedVersions as $version) {
                        Helper::new()->skipMigrationVersion($dependencyFactorySecondaryDb->getConnection(), $version);
                    }
                }

                $this->io->block(["Done."], 'info');
            } catch (Exception $e) {
                $this->io->warning('There were problems during migration db Onload.');
                $this->io->warning($e->getMessage());
            }

            return true;
        }
        return false;
    }

    private function configureIO(array $inputArgs = null, OutputInterface $output = null): void
    {
        $this->input = ($inputArgs === null) ? new ArgvInput() : new ArgvInput($inputArgs);
        $this->output = $output ?? new ConsoleOutput();
        if (true === $this->input->hasParameterOption(['--quiet', '-q'], true)) {
            $this->output->setVerbosity(OutputInterface::VERBOSITY_QUIET);
        }
        $this->io = new SymfonyStyle($this->input, $this->output);
    }

    private function buildMigrationConfiguration(): Configuration
    {
        $configurationMainDb = new Configuration();
        $configurationMainDb->addMigrationsDirectory($this->migrationsNamespace, $this->migrationsPath);
        $configurationMainDb->setAllOrNothing(true);
        $configurationMainDb->setCheckDatabasePlatform(false);

        $storageConfigurationMainDb = new TableMetadataStorageConfiguration();
        $storageConfigurationMainDb->setTableName($this->migrationsDbTable);
        $configurationMainDb->setMetadataStorageConfiguration($storageConfigurationMainDb);
        return $configurationMainDb;
    }

    private function getTemporaryDbName(): string
    {
        $tmpDbName = $this->cfg()->get('core->db->database') . '_' . DbMigrationConstants::DB_MIG_DB_FOR_DIFF;
        return substr($tmpDbName, 0, 64);
    }

    public function recreateTemporaryDb(Connection $connectionDb): void
    {
        $this->io->block(["Building a real DB to execute schema analyse against."], 'step');
        try {
            $connectionDb->executeStatement('DROP DATABASE IF EXISTS `' . $this->temporaryDatabaseForDiff . '` ;');
            $connectionDb->executeStatement(
                'CREATE DATABASE `' . $this->temporaryDatabaseForDiff . '` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ ;'
            );
            $this->io->block(["Done."], 'info');
        } catch (Exception $e) {
            $dbConfig = $this->prepareDbConfig();
            $this->io->error("Recreate of temporary database failed.");
            $this->io->warning(
                "Be sure that user: " . $dbConfig['user']
                . ' has privileges to drop and create database: ' . $this->getTemporaryDbName()
            );
            $this->io->warning([
                "Execute in mysql: ",
                "GRANT ALL PRIVILEGES ON " . $this->getTemporaryDbName() . ".* TO '" . $dbConfig['user'] . "'@'%';"
            ]);
            $this->io->warning([
                "If you are DEVELOPER, and want more flexibility during local development. Grant all to user: ",
                "GRANT ALL PRIVILEGES ON *.* TO '" . $dbConfig['user'] . "'@'%';"
            ]);
            $this->io->warning("Shutting down command.");
            die();
        }
    }

    public function executeSql(Connection $connectionDb, string $sql): bool
    {
        try {
            $connectionDb->executeStatement($sql);
            return true;
        } catch (Exception $e) {
            $this->io->warning($e->getMessage());
            return false;
        }
    }

    #[ArrayShape([
        'dbname' => "string",
        'user' => "string",
        'password' => "string",
        'host' => "string",
        'port' => "string",
        'charset' => "string",
        'driver' => "string"
    ])] private function prepareDbConfig(): array
    {
        return [
            'dbname' => $this->cfg()->get('core->db->database'),
            'user' => $this->cfg()->get('core->db->username'),
            'password' => $this->cfg()->get('core->db->password'),
            'host' => $this->cfg()->get('core->db->server'),
            'port' => $this->cfg()->get('core->db->port'),
            'charset' => $this->cfg()->get('core->db->encoding'),
            'driver' => 'pdo_mysql',
        ];
    }
}
