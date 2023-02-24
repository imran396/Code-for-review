<?php

require_once path()->classes() . '/Sam/Core/Service/SingletonInterface.php';
require_once path()->classes() . '/Sam/Core/Service/Singleton.php';
require_once path()->classes() . '/Sam/Installation/Config/Repository/ConfigRepositoryInterface.php';
require_once path()->classes() . '/Sam/Installation/Config/Repository/ConfigRepository.php';

use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * @return ConfigRepository
 */
function cfg(): ConfigRepository
{
    return ConfigRepository::getInstance();
}
