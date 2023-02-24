<?php
/**
 * SAM-5306: Local installation correctness check
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 27, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Validate\Cli\Command\Fs;

use Sam\File\FolderManagerAwareTrait;
use Sam\File\Manage\LocalFileManagerCreateTrait;
use Sam\Image\ImageHelper;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Installation\Validate\Cli\Command\Base\CommandBase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Sam\Core\Constants;

/**
 * This class is a console command that checks directories existence and their permissions
 *
 * Class CheckFSCommand
 * @package Sam\Installation\Cli
 */
class FsCommand extends CommandBase
{
    use ConfigRepositoryAwareTrait;
    use FolderManagerAwareTrait;
    use LocalFileManagerCreateTrait;

    public const NAME = 'fs';

    /** @var array */
    private array $writableDirs;

    /** @var array|string[] */
    protected array $cfgFiles;

    /**
     * Must be writable
     * @var string[]
     */
    protected const DOC_ROOT_RELATIVE_PATHS = [
        'general/data',
        'lot-info',
        'sitemap/cache',
        'images/auction',
        'images/lot',
        'images/lot/0',
        'images/settings',
        'images/text_images',
        'reseller_data'
    ];

    /** @var string[] */
    private array $generatedFolders;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->writableDirs = [
            path()->log(),
            path()->logReport(),
            path()->temporary(),
            path()->cache(),
            path()->session(),
            path()->upload(),
            path()->dynamicLanguage(),
            path()->customFieldTranslationMaster(),
        ];

        $this->generatedFolders = [
            path()->var(),
            path()->log(),
            path()->logReport(),
            path()->upload(),
            path()->temporary(),
            path()->defaultLotImages(),
            path()->cache(),
            path()->session(),
            path()->unitTestTemporary(),
            path()->functionalTestTemporary(),
            path()->dynamicLanguage(),
            path()->customFieldTranslationMaster(),
        ];

        $this->cfgFiles = [
            'core.local.php',
            'csv.local.php',
        ];
    }

    /**
     * Configures command.
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption('fix', null, InputOption::VALUE_NONE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $needCreateDirectories = $input->getOption('fix');
        if ($needCreateDirectories) {
            $this->createDirectories($output);
            $this->updatePermissions();
            $this->createOrUpdateCfgFiles($output);
            $this->fixDefaultLotImages();
            $this->fixDefaultAuctionImages();
            $this->fixDefaultAccountImages();
        }
        $currentUser = $this->getCurrentUser();
        $hasErrors = false;
        foreach ($this->writableDirs as $pathKey) {
            if (!$this->processChecking($pathKey, $output)) {
                $hasErrors = true;
            }
        }

        foreach (self::DOC_ROOT_RELATIVE_PATHS as $relativePath) {
            if (!$this->processChecking($this->makeDocRootPath($relativePath), $output)) {
                $hasErrors = true;
            }
        }

        if (
            $this->hasCfgPermissionErrors($output)
            || !$this->hasDefaultAuctionImages($output)
            || !$this->hasDefaultLotImages($output)
            || !$this->hasDefaultAccountImages($output)
        ) {
            $hasErrors = true;
        }

        if (!$hasErrors) {
            $output->writeln(sprintf('<info>All dirs are writable for user: %s</info>', $currentUser));
        }

        return Constants\Cli::EXIT_SUCCESS;
    }

    /**
     * @param OutputInterface $output
     * @return void
     */
    protected function createDirectories(OutputInterface $output): void
    {
        foreach ($this->generatedFolders as $path) {
            $isChmod = false;
            if (!is_dir($path)) {
                $oldMask = umask(0);
                $isChmod = @mkdir($path);
                umask($oldMask);
                if (
                    !$isChmod
                    && !is_dir($path)
                ) {
                    log_error('Error on creating directory ' . $path);
                    $output->writeln(
                        sprintf('<error>Error on creating directory %s</error>', $path)
                    );
                }
                $output->writeln(
                    sprintf('<info>Created directory %s</info>', $path)
                );
            }
            if ($isChmod) {
                $this->getFolderManager()->chmodRecursively($path);
            }
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function createOrUpdateCfgFiles(OutputInterface $output): void
    {
        foreach ($this->cfgFiles as $cfgFileName) {
            $filePath = path()->configuration() . '/' . $cfgFileName;
            if (!file_exists($filePath)) {
                continue;
            }
            if (!is_writable($filePath)) {
                $this->createLocalFileManager()->applyDefaultPermissions($filePath);
                $output->writeln(
                    sprintf(
                        '<info>Updated cfg file permission %s</info>',
                        $filePath
                    )
                );
            }
        }
    }

    /**
     * @return void
     */
    protected function updatePermissions(): void
    {
        foreach ($this->writableDirs as $pathKey) {
            $this->getFolderManager()->chmodRecursively($pathKey);
        }
        foreach (self::DOC_ROOT_RELATIVE_PATHS as $relativePath) {
            $this->getFolderManager()->chmodRecursively($this->makeDocRootPath($relativePath));
        }
    }

    /**
     * @param string $path
     * @param OutputInterface $output
     * @return bool
     */
    private function processChecking(string $path, OutputInterface $output): bool
    {
        $currentUser = $this->getCurrentUser();
        if (!is_dir($path)) {
            $output->writeln(
                sprintf(
                    '<error>Dir "%s" does not exist</error>',
                    $path
                )
            );
            return false;
        }
        if (!is_writable($path)) {
            $output->writeln(
                sprintf(
                    '<error>Dir "%s" is not writable for user: %s</error>',
                    $path,
                    $currentUser
                )
            );
            return false;
        }

        $output->writeln(
            sprintf(
                '<comment>Dir "%s" is writable for user: %s</comment>',
                $path,
                $currentUser
            ),
            OutputInterface::VERBOSITY_VERBOSE
        );

        return true;
    }

    /**
     * @param OutputInterface $output
     * @return bool
     */
    protected function hasCfgPermissionErrors(OutputInterface $output): bool
    {
        $hasErrors = false;
        foreach ($this->cfgFiles as $cfgFileName) {
            $filePath = path()->configuration() . '/' . $cfgFileName;
            if (!file_exists($filePath)) {
                continue;
            }
            if (!is_writable($filePath)) {
                $output->writeln(
                    sprintf(
                        '<error>Config file %s does not exist or is not writable</error>',
                        $filePath
                    )
                );
                $hasErrors = true;
            }
        }
        return $hasErrors;
    }

    /**
     * @param OutputInterface $output
     * @return bool
     */
    protected function hasDefaultLotImages(OutputInterface $output): bool
    {
        $hasDefaultImages = true;
        $imagePrefixes = [
            '0_',
            'coming_soon_'
        ];
        foreach ($imagePrefixes as $imagePrefix) {
            foreach ($this->cfg()->get('core->image->thumbnail') as $key => $value) {
                $size = strtolower(str_replace('size', '', $key));
                $fileName = $imagePrefix . $size . '.jpg';
                $filePath = path()->defaultLotImages() . '/' . $fileName;
                if (!file_exists($filePath)) {
                    $hasDefaultImages = false;
                    $output->writeln(
                        sprintf(
                            '<error>Default lot image %s does not exist</error>',
                            $fileName
                        )
                    );
                }
            }
        }
        return $hasDefaultImages;
    }

    /**
     * @param OutputInterface $output
     * @return bool
     */
    protected function hasDefaultAuctionImages(OutputInterface $output): bool
    {
        $hasDefaultImages = true;
        foreach ($this->cfg()->get('core->image->thumbnail') as $key => $value) {
            $size = strtolower(str_replace('size', '', $key));
            $fileName = '0_' . $size . '.jpg';
            $filePath = path()->defaultAuctionImages() . '/' . $fileName;
            if (!file_exists($filePath)) {
                $hasDefaultImages = false;
                $output->writeln(
                    sprintf(
                        '<error>Default auction image %s does not exist</error>',
                        $fileName
                    )
                );
            }
        }
        return $hasDefaultImages;
    }

    /**
     * @param OutputInterface $output
     * @return bool
     */
    protected function hasDefaultAccountImages(OutputInterface $output): bool
    {
        $hasDefaultImages = true;
        foreach ($this->cfg()->get('core->image->thumbnail') as $key => $value) {
            $size = strtolower(str_replace('size', '', $key));
            $fileName = '0_' . $size . '.jpg';
            $filePath = path()->defaultAccountImages() . '/' . $fileName;
            if (!file_exists($filePath)) {
                $hasDefaultImages = false;
                $output->writeln(
                    sprintf(
                        '<error>Default settings image %s does not exist</error>',
                        $fileName
                    )
                );
            }
        }
        return $hasDefaultImages;
    }

    /**
     * @return void
     */
    protected function fixDefaultLotImages(): void
    {
        $imageHelper = ImageHelper::new();
        $imageHelper->createDefaultLotImageThumbsFromStub();
        $imageHelper->createComingSoonLotImageThumbsFromStub();
    }

    /**
     * @return void
     */
    protected function fixDefaultAuctionImages(): void
    {
        $imageHelper = ImageHelper::new();
        $imageHelper->createDefaultAuctionImageThumbsFromStub();
    }

    /**
     * @return void
     */
    protected function fixDefaultAccountImages(): void
    {
        $imageHelper = ImageHelper::new();
        $imageHelper->createDefaultAccountImageThumbsFromStub();
    }


    /**
     * @return string
     */
    protected function getCurrentUser(): string
    {
        if (function_exists('posix_getpwuid')) {
            /** @noinspection PhpComposerExtensionStubsInspection */
            $processUser = posix_getpwuid(posix_geteuid());
            $currentUser = $processUser['name'];
        } else {
            $currentUser = getenv('USERNAME');
        }
        return $currentUser;
    }

    /**
     * Make absolute path to directory in public document root.
     * @param string $relativePath
     * @return string
     */
    protected function makeDocRootPath(string $relativePath): string
    {
        return path()->docRoot() . '/' . $relativePath;
    }
}
