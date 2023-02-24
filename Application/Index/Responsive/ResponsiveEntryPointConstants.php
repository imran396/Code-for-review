<?php
/**
 * SAM-5677: Extract logic from web entry points index.php
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Index\Responsive;

/**
 * Class ResponsiveEntryPointConstants
 * @package
 */
class ResponsiveEntryPointConstants
{
    // List of routes, where we shouldn't start session for anonymous user.
    // array value (true) doesn't mean anything, only keys do.
    // see. http://stackoverflow.com/questions/2473989/list-of-big-o-for-php-functions/2484455#2484455
    public static array $noSessionRoutes = [
        //'/image/view' => true, // no such route anymore, we handle images by /wwwroot/images/image.php
        '/billing-opayo/start-three-d' => true,
        '/lot-item/catalog' => true,
        '/lot-item/group' => true,
        '/api/file' => true,
        '/audio' => true,                   // wwwroot\audio\sound.php
        '/sitemap' => true,                 // wwwroot\sitemap\
        '/location' => true,                // wwwroot\location\index.php
        '/m/app.php' => true,               // wwwroot\m\app.php
        '/php/_core/image.php' => true,     // wwwroot\php\_core\image.php
        '/sync/auction' => true, // sync auction list
        '/sync/lot' => true,
        '/check-for-user.php' => true,      // BPAV endpoint
        '/health' => true,                  // health check end-point (SAM-7956)
    ];

    /**
     * SAM-1196: pages must start the session even for anonymous visitors
     * Pages that send data via POST should supply csrf-token, that is stored in session on page load (SAM-5743).
     * Pages that show captcha, store it in session.
     */
    public static array $mustSessionRoutes = [
        '/auctions/ask-question' => true,
        '/auctions/tell-friend' => true,
        '/forgot-password' => true,
        '/forgot-username' => true,
        '/login' => true,
        '/reset-password' => true,
        '/signup' => true,
        '/watchlist/add' => true,
    ];

    public static array $cacheRoutes = [
        '/lot-item/catalog' => true,
    ];

    // pages, that should be cached for anonymous visitor
    public static array $anonymousCacheRoutes = [
        '/' => true,
        '/auctions/list' => true,
        '/auctions/info' => true,
        '/auctions/catalog' => true,
        '/auctions/live-sale' => true,
        '/lot-details/index' => true,
        '/lot-details/other-lots' => true,
        '/search' => true,
    ];
}
