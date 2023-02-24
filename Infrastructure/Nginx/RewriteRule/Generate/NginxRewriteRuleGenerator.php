<?php
/**
 * SAM-7672: Limit request routes that will invoke the application
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Infrastructure\Nginx\RewriteRule\Generate;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Constants;
use Sam\Core\Web\ControllerAction\ControllerActionCollection;
use Sam\File\Manage\LocalFileManager;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Installation\Config\Repository\DefaultedConfigRepository;

/**
 * Class NginxRewriteRuleGenerator
 * @package Sam\Infrastructure\Nginx\RewriteRule\Generate
 */
class NginxRewriteRuleGenerator extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function generate(): void
    {
        $fileManager = LocalFileManager::new();

        $responsiveRoutes = $this->produceRoutes(Ui::new()->constructWebResponsive());
        $adminRoutes = $this->produceRoutes(Ui::new()->constructWebAdmin());
        $routes = array_merge($adminRoutes, $responsiveRoutes);

        $fullPath = path()->configuration() . '/nginx/rewrite_rules.conf';
        $localPath = substr($fullPath, strlen(path()->sysRoot()));

        $rulesForResponsive = '';
        $rulesForAdmin = '';

        foreach ($routes as $route) {
            [$uiDir, $controller, $action] = $this->parseRoute($route);
            $params = DefaultedConfigRepository::new()->detectRuntimeParams($uiDir, $controller, $action);

            $phpValue = $this->makePhpValue($params);
            $maxBodySize = $this->makeMaxBodySize($params);

            if (str_contains($route, '/index')) {
                $route = preg_replace('/\/index/', '/?(index(/(.*)?)?)?', $route);
            } else {
                $route .= '(/(.*)?)?';
            }
            if (str_contains($route, '/admin')) {
                $rulesForAdmin .= <<<EOT

location ~ ^$route$ {
    index index.php index.html index.htm;
    try_files \$uri \$uri/ /admin/index.php?\$args;$maxBodySize$phpValue
}

EOT;
            } else {
                $rulesForResponsive .= <<<EOT

location ~ ^$route$ {
    index index.php index.html index.htm;
    try_files \$uri \$uri/ /index.php?\$args;$maxBodySize$phpValue
}

EOT;
            }
        }

        $rules = <<<EOT
### DO NOT EDIT CURRENT FILE. Instead Edit | Run /bin/generate/nginx_rewrite_rules.php ###

# Deny access to hidden files
location ~ /\.(?!well-known).* {
    deny all;
    access_log off;
    log_not_found off;
    return 404;
}

location ~ ^//?$ {
    index index.php index.html index.htm;
    try_files \$uri \$uri/ /index.php?\$args;
}

location ~ \.css {
    add_header  Content-Type    text/css;
}

### This rule must come before image processing rule(gif|jpe?g|png) ###
location ~ ^/downloads/ {
    rewrite ^/downloads/lot/(\d+)/field/(\d+)/(.+)$ /download/lot-custom-field?&id=$1&filename=$3&cust_field_id=$2 redirect;
    rewrite ^/downloads/auction/(\d+)/(.+)$ /download/auction-custom-field?id=$1&filename=$2 redirect;
    rewrite ^/downloads/user/(\d+)/(.+)$ /download/user-custom-field?uid=$1&filename=$2 redirect;

    try_files \$uri =404;
}

location ~* \.(gif|jpe?g|png)$ {
   rewrite ^/php/_core/image.php/ /php/_core/image.php?\$args last;
   expires max;
   try_files \$uri \$uri/ @images;
}

location ~* \.(ico|svg|eot|otf|woff|woff2|ttf|ogg|js|css)$ {
   expires max;
}

$rulesForResponsive

$rulesForAdmin

location  /admin/items.csv {
    client_max_body_size 3m;
    rewrite ^/admin/items.csv$ /admin/items.php;
    index index.php index.html index.htm;
    try_files \$uri \$uri/ /admin/index.php?\$args;
}

location  /admin/items-timed.csv {
    client_max_body_size 3m;
    rewrite ^/admin/items-timed.csv$ /admin/items-timed.php;
    index index.php index.html index.htm;
    try_files \$uri \$uri/ /admin/index.php?\$args;
}

location   /admin/inventory-items.csv {
    client_max_body_size 3m;
    rewrite ^/admin/inventory-items.csv$ /admin/inventory-items.php;
    index index.php index.html index.htm;
    try_files \$uri \$uri/ /admin/index.php?\$args;
}

# disable keepalive on auto-update requests
location /sync/ {
    keepalive_requests 0;

    try_files \$uri \$uri/ =404;
}

location /audio/ {
    rewrite ^/audio/(auction)/(\d+)/(\d+)/?(.+(\.([a-z][a-z][a-z][a-z]?))?)?$ /audio/sound?class=$1&id=$2&account=$3&filename=$4&extension=$5 redirect;
    rewrite ^/audio/(rtbmessage)/(\d+)/(\d+)/?$ /audio/sound?class=$1&id=$2&account=$3 redirect;

    try_files \$uri =404;
}

location /consignor/ {
    rewrite ^/consignor/items.csv$ /consignor/items.php;
    rewrite ^/consignor/items-timed.csv$ /consignor/items-timed.php;
    rewrite ^/consignor/inventory.csv$ /consignor/inventory.php;

    try_files \$uri =404;
}

location /feed/ {
    try_files \$uri \$uri/ /index.php?\$args;
}

location ^~ /feed/index.php {
    return 302 /feed/index\$is_args\$args;
}

location /general/ {
    try_files \$uri =404;
}

location ^~ /svninfo.php {
    return 302 /info/svninfo.php;
}

location ^~ /keep_alive.php {
    return 302 /info/keep_alive.php;
}

location ^~ /time/utimestamp.php {
    return 302 /info/utimestamp.php;
}

location ^~ /whatismyip.php {
    return 302 /info/whatismyip.php;
}

location @images {
    #RewriteCond %{REQUEST_URI} ^/images/lot/(\d{1,4})/(\d+)_(s|m|l|xl).jpg$
    #RewriteCond %{DOCUMENT_ROOT}/images/lot/%1/%2_%3.jpg !-f
    #RewriteRule ^(lot)/(\d{1,4})/((\d+)_(s|m|l|xl).(jpg))$ image.php?class=$1&option=$3 [L]

    rewrite ^/images/lot/((\d+)/(\d+)_(s|m|l|xl).jpg)$ /images/image.php?class=lot&option=$1;

    rewrite ^/images/auction/((\d+)_(s|m|l|xl).(jpg))$ /images/image.php?class=auction&option=$1;

    rewrite ^/images/location-logo/(\d+).(jpg)$ /images/image.php?class=location-logo&option=$1;

    rewrite ^/images/settings/account_(\d+).(jpg)$ /images/image.php?class=account&option=$1;

    rewrite ^/images/(user|logo|invoice|settlement)/(\d+)/(.+)/(.+(\.([a-z][a-z][a-z][a-z]?))?)$ /images/image.php?class=$1&id=$2&option=$3&filename=$4&extension=$6;

    rewrite ^/images/(auction-image)/(\d+)/(\d+[a-z]?)/(\d+)/(.+)/(.+(\.([a-z][a-z][a-z][a-z]?))?)$ /images/image.php?class=$1&auction_id=$2&id=$3&image_num=$4&option=$5&filename=$6&extension=$7;

    rewrite ^/images/captcha.jpg$ /image/captcha redirect;

    try_files \$uri =404;

}

location ~ ^/lot-info/rtb-\d+\.log {
    deny all;
}

location /lot-info/ {
    try_files \$uri =404;
}

location /min/ {
    rewrite ^/min/([bfg]=.+) /min/index.php?$1;
}

location /reportico/index.php {
    allow all;
}

location /reportico/run.php {
    allow all;
}

location /reportico {
    location ~ \.php$ {
        # drop rest of the world
        deny all;
    }
    return 302 /reportico/index.php\$is_args\$args;
}

location /reportico/dyngraph_pchart.php {
    allow all;
}

location /reportico/tcpdf/tools/ {
    deny all;
}

location /sitemap/ {
    if (!-e \$request_filename){
        rewrite ^/sitemap/cache/(index-)(\d+)(.xml)?$ /sitemap/generate-index?q=$2 redirect;
        rewrite ^/sitemap/cache/(auction-)(\d+)(.xml)?$ /sitemap/generate-auction?q=$2 redirect;
    }
}

### Legacy redirect rules
location ~/m/index/?(.*)$ {
   return 301 /$1\$is_args\$args;
}

location ~/m/register/?(.*)$ {
   return 301 /signup$1\$is_args\$args;
}

location ~/m/watchlist/?(.*)$ {
   return 301 /my-items/watchlist$1\$is_args\$args;
}

location ~/view-auctions/catalog/id/(\d+)/lot/(\d+)/?$ {
   return 301 /lot-details/index/catalog/$1/lot/$2\$is_args\$args;
}

location ~/view-auctions/individual-lots {
   return 301 /search\$is_args\$args;
}

location ~/view-auctions/?(.*)$ {
   return 301 /auctions/$1\$is_args\$args;
}

location ~/m/other-lots/?(.*)$ {
   return 301 /other-lots/$1\$is_args\$args;
}

location ~/m/lot-details/?(.*)$ {
   return 301 /lot-details/$1\$is_args\$args;
}

location ~/m/(access-error|forgot-password|forgot-username|index|login|logout|lot-details|my-alerts|my-invoices|my-items|my-settlements|notifications|profile|reset-password|search|signup)?(.*)$ {
   return 301 /$1\$is_args\$args;
}

EOT;


        $fileManager->write($rules, $localPath);
    }

    /**
     * Create client_max_body_size directive for specific routes. (SAM-9425)
     * client_max_body_size - sets the maximum allowed size of the client request body.
     * If the size in a request exceeds the configured value, the 413 (Request Entity Too Large) error is returned to the client.
     * @param array $params
     * @return string
     */
    protected function makeMaxBodySize(array $params): string
    {
        return PHP_EOL . sprintf('    client_max_body_size %dm;', $params[Constants\Runtime::KEY_NGINX]['client_max_body_size']);
    }

    protected function makePhpValue(array $params): string
    {
        $phpValue = [];
        foreach ($params[Constants\Runtime::KEY_PHP_VALUE] as $key => $value) {
            $phpValue[] = $key . ' = ' . $value;
        }
        return PHP_EOL . sprintf('    set $phpValue "%s";', implode(' \n ', $phpValue));
    }

    protected function parseRoute(string $route): array
    {
        $routeParts = explode('/', $route);
        if ($routeParts[1] === Constants\Application::UIDIR_ADMIN) {
            $uiDir = Constants\Application::UIDIR_ADMIN;
            $controller = $routeParts[2];
            $action = $routeParts[3];
        } else {
            $uiDir = Constants\Application::UIDIR_RESPONSIVE;
            $controller = $routeParts[1];
            $action = $routeParts[2];
        }
        return [$uiDir, $controller, $action];
    }

    protected function produceRoutes(Ui $ui): array
    {
        $uiControllerActions = [
            Constants\Application::UI_RESPONSIVE => [Constants\ResponsiveRoute::CONTROLLER_ACTIONS, [$this, 'toResponsiveRoute']],
            Constants\Application::UI_ADMIN => [Constants\AdminRoute::CONTROLLER_ACTIONS, [$this, 'toAdminRoute']],
        ];
        [$controllerActions, $transformFn] = $uiControllerActions[$ui->value()];
        foreach ($controllerActions as $controller => $actions) {
            $controllerActions[$controller] = array_flip($actions);
        }
        $caCollection = ControllerActionCollection::new()->construct($controllerActions);
        $caCollection->applyToAll($transformFn);
        return $caCollection->flatten();
    }

    /**
     * Public because passed as callable.
     * @param string $controller
     * @param string $action
     * @param mixed $value
     * @return string
     */
    public function toAdminRoute(string $controller, string $action, mixed $value): string
    {
        return "/admin/{$controller}/{$action}";
    }

    /**
     * Public because passed as callable.
     * @param string $controller
     * @param string $action
     * @param mixed $value
     * @return string
     */
    public function toResponsiveRoute(string $controller, string $action, mixed $value): string
    {
        return "/{$controller}/{$action}";
    }
}
