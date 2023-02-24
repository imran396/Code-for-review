<?php
/**
 * DefaultedConfigRepository, get data from runtime.php
 *
 * SAM-6397: Runtime config options
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jun 16, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Installation\Config\Repository;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DefaultedConfigRepository
 * @package Sam\Installation\Config\Repository
 */
class DefaultedConfigRepository extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * @var string[]
     */
    protected const NGINX = [
        'client_max_body_size'
    ];
    /**
     * @var string[]
     */
    protected const PHP_VALUE = [
        'max_execution_time',
        'memory_limit',
        'post_max_size',
        'upload_max_filesize'
    ];
    protected const PATH_SEPARATOR = '->';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function detectCsvUploadUrlUploadMaxFilesize(): string
    {
        $uiDir = Constants\Application::UIDIR_ADMIN;
        $controller = Constants\AdminRoute::C_MANAGE_CSV_IMPORT;
        $action = Constants\AdminRoute::AMCI_UPLOAD_FILES;
        $params = $this->detectRuntimeParams($uiDir, $controller, $action);
        return $params[Constants\Runtime::KEY_PHP_VALUE]['upload_max_filesize'];
    }

    public function detectCliRuntimeParams(string $script, array $folders): array
    {
        $params = [];
        $default = Constants\Runtime::KEY_DEFAULT;
        $runtime = 'runtime';
        $paths = $this->definePaths($folders);

        $params = $this->getLevelParams(implode(self::PATH_SEPARATOR, [$runtime, current($paths), $script]), $params);
        foreach ($paths as $path) {
            $params = $this->getLevelParams(implode(self::PATH_SEPARATOR, [$runtime, $path, $default]), $params);
        }

        return $params;
    }

    public function detectRuntimeParams(string $uiDir, string $controller, string $action): array
    {
        $params = [];
        $default = Constants\Runtime::KEY_DEFAULT;
        $runtime = 'runtime';
        $params = $this->getLevelParams(implode(self::PATH_SEPARATOR, [$runtime, $uiDir, $controller, $action]), $params);
        $params = $this->getLevelParams(implode(self::PATH_SEPARATOR, [$runtime, $uiDir, $controller, $default]), $params);
        $params = $this->getLevelParams(implode(self::PATH_SEPARATOR, [$runtime, $uiDir, $default]), $params);
        $params = $this->getLevelParams(implode(self::PATH_SEPARATOR, [$runtime, $default]), $params);
        $this->checkMissingParams($params);
        return $params;
    }

    protected function checkMissingParams(array $params): void
    {
        $missingNginxParam = array_diff(self::NGINX, array_keys($params[Constants\Runtime::KEY_NGINX]));
        if ($missingNginxParam) {
            throw new InvalidConfigOption('Cannot find nginx param: ' . implode(', ', $missingNginxParam));
        }
        $missingPhpValue = array_diff(self::PHP_VALUE, array_keys($params[Constants\Runtime::KEY_PHP_VALUE]));
        if ($missingPhpValue) {
            throw new InvalidConfigOption('Cannot find phpValue: ' . implode(', ', $missingPhpValue));
        }
    }

    protected function getLevelParams(string $optionKey, array $params): array
    {
        $option = $this->cfg()->get($optionKey);
        if ($option) {
            $params = array_replace_recursive($option->toArray(), $params);
        }
        return $params;
    }

    /**
     * Create paths from array of folders
     * Example [folder1, folder2, folder3] => [folder1->folder2->folder3, folder1->folder2, folder1]
     * @param array $folders
     * @return array
     */
    protected function definePaths(array $folders): array
    {
        $path = [];
        $result = [];
        foreach ($folders as $folder) {
            $path[] = $folder;
            $result[] = implode(self::PATH_SEPARATOR, $path);
        }
        return array_reverse($result);
    }
}
