<?php

namespace Sam\Installation\Config\Edit\Meta\Configuration;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * SAM-4886: Local configuration files management page
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/2/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Meta options description:
 *
 * for getting access to config options please use following syntax: 'admin->dashboard->closedAuctions'.
 * This syntax means that yoo want to get access for ['admin' => ['dashboard' => ['closedAuctions' => []  ]]] config option.
 * '->' - this is a meta option path delimiter and can be configured in Constants\Installation::META_OPTION_PATH_DELIMITER
 * Please use same delimiter as defined in this constant.
 *
 *
 * Mata options attributes description:
 *
 * 1. 'description' - config option description in web-interface form;
 *
 * 2. 'editComponent' - Web-interface form element type for config option. Can accept only 'multiline' if
 * data type is Constants\Type::T_ARRAY; see available controls in Constants\Installation::$validEditComponents.
 * --------------
 * If for config option isset 'knownSet' and 'knownSetNames', 'editComponent' can accept only following values:
 * Constants\Installation::ECOM_RADIO,
 * Constants\Installation::ECOM_SELECT,
 * Constants\Installation::ECOM_MULTISELECT
 * if 'editComponent' have not been set manually in 'editComponent' attribute for config option, 'editComponent' will be
 * setup automatically. In this case if count values in 'knownSet' or 'knownSetNames' will be grater
 * than in Constants\Installation::KNOWN_SET_CTRL_RADIOBUTTON_MAX_ITEMS (6 by default) will be used
 * Constants\Installation::ECOM_SELECT, otherwise will be used Constants\Installation::ECOM_RADIO. *
 *
 * 3. 'editable' - enable\disable  config option for editing in web-interface form. This option will
 * be shown in wed-interface config form but not available for editing. Available values - true/false;
 *
 * 4-5. 'knownSet' and 'knownSetNames' - list of pre-defined values for config option. This values will be displayed
 * in web-interface form as a radio buttons list or a select\multi-select list.
 * for 'knownSet' you must use simple array ['value', 0, 'text', 99, 56, ... etc.]. See example \Sam\Core\Constants\Report::$orderDirections
 * for 'knownSetNames' you must use associative array.  See example \Sam\Core\Constants\AuctionParameters::$buyNowTimedRestrictionNames
 * [
 *      'value' =>'value title in web-interface form', // for web-interface config form for rendering this name will
 *                                                     // be used following template
 *                                                     // \Sam\Core\Constants\Admin\InstallationSettingEditConstants::WEB_KNOWNSET_NAMES_TITLE_TMPL
 *      159 => 'value title in web-interface form'
 * ]
 *
 * 6. 'type' - Custom data type for config option value. If config option value contains an array, you must setup 'type'
 * to Constants\Type::T_ARRAY for correct rendering in web-interface form;
 *
 * 7. 'constraints' - array with validation rules.
 * Used for validate input from web-interface form request data.
 * All used validations must be added to Constants\Installation under Validation section constants.
 * If some validation have any arguments - that validation must be added to Constants\Installation::$withArgumentValidations
 * static property.
 * All additional validation methods (except for 'required' validation) must exists
 * in \Sam\Installation\Config\Edit\Validate\OptionValueChecker.
 * Example usage :
 * 'constraints' => [
 * 'required',  // mark config option as required
 * 'hex',  // validate for hex value
 * 'minLength' => 5, //validate for min length
 * 'maxLength' => 10, //validate for max length
 * 'range' => [100, 300], //validate for range
 * 'regExp' => '/^[A-F\d]{5,10}$/', //validate for regular expression match
 * ],
 *
 * 8. 'visible' - show\hide config option in web-interface form. Available values - true/false;
 */
class CoreMetaConfiguration extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array[]
     */
    public function get(): array
    {
        $description = Constants\Installation::META_ATTR_DESCRIPTION;
        $knownSet = Constants\Installation::META_ATTR_KNOWN_SET;
        $knownSetNames = Constants\Installation::META_ATTR_KNOWN_SET_NAMES;
        $editComponent = Constants\Installation::META_ATTR_EDIT_COMPONENT;
        $editable = Constants\Installation::META_ATTR_EDITABLE;
        $type = Constants\Installation::META_ATTR_TYPE;
        $constraints = Constants\Installation::META_ATTR_CONSTRAINTS;
        $visible = Constants\Installation::META_ATTR_VISIBLE;

        return [
            'core->admin->auction->lastBids->refreshTimeout' => [
                $description => 'Last Bids Report refresh interval in seconds',
            ],
            'core->admin->auction->lots->quickEdit->lotLimit' => [
                $description => 'Limit lot count at page, when Quick-edit function available at Admin Auction Lot List page (SAM-1547)',
            ],
            'core->admin->auction->lots->syncTimeout' => [
                $description => 'Data synchronization interval in seconds at Admin Auction Lot List page',
            ],
            'core->admin->auction->lots->summary->alwaysActual' => [
                $description => <<<TEXT
true - recalculate auction cache, if it is stale (SAM-6035). 
Enabling may have negative performance impact and could potentially result in database locks during active bidding periods
TEXT
            ],
            'core->admin->dashboard->closedAuctions' => [
                $description => 'Maximum count of closed auctions shown at admin dashboard, SAM-1652',
            ],
            'core->admin->invoice->disableMarkUnsoldOnDelete' => [
                $description => <<<TEXT
If enabled - we will disable "Would you also like to mark all the lots in this invoice as 'Unsold' ?" 
dialog box at "admin/manage-invoices" page during delete invoice action. And will process delete invoice without any related confirmation from user.
TEXT

            ],
            'core->admin->invoice->generator->limitInSeconds' => [
                $description => 'Sets how long it should generate invoices in a single ajax call. It will generate at least 1 invoice in each call.',
            ],
            'core->admin->report->customLots->fields' => [
                $type => Constants\Type::T_ARRAY,
                $description => 'Default report fields and order.',
            ],
            'core->admin->report->customLots->order->direction' => [
                $knownSet => Constants\Report::$orderDirections,
                $editComponent => Constants\Installation::ECOM_RADIO,
            ],
            //admin END

            //app START
            'core->app->csrf->originRefererCheck->exclude' => [
                $type => Constants\Type::T_ARRAY,
            ],
            'core->app->csrf->originRefererCheck->excludePath' => [
                $type => Constants\Type::T_ARRAY
            ],
            'core->app->csrf->synchronizerToken->exclude' => [
                $type => Constants\Type::T_ARRAY
            ],
            'core->app->dbCacheUpdateMaxExecutionTime' => [
                $description => 'SAM-2647 Max execution time for script, that updates auction_cache.',
            ],
            'core->app->errorFriendlyPage->path' => [
                $editable => false,
            ],
            'core->app->errorFriendlyPage->pathFeedback' => [
                $editable => false,
            ],
            'core->app->extendFromCurrentTime->enabled' => [
                $description => 'SAM-1945: Extend from current time.',
            ],
            'core->app->formState->longevityTracking->enabled' => [
                $description => 'When enabled, we unset QForm object type properties before its serialization and storing in FormState for subsequent action (SAM-4898)',
            ],
            'core->app->header->xFrameOption' => [
                $description => 'Value for X-Frame-Options HTTP response header',
            ],
            'core->app->header->xPoweredBy' => [
                $description => 'Value for X-Powered-By HTTP response header',
            ],
            'core->app->httpHost' => [
                $description => 'Main account host',
                $editable => false,
            ],
            'core->app->httpHostIgnoreServerPort' => [
                $description => 'Used for host url building. We ignore the $_SERVER[SERVER_PORT], when this option is true.',
            ],
            'core->app->httpRequest->log->enabled' => [
                $description => 'SAM-5240: should enable / disable output to log file',
            ],
            'core->app->httpRequest->log->level' => [
                $knownSetNames => Constants\Debug::$levelNames,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'SAM-5240: should define log level, when log enabled',
            ],
            'core->app->inlineHelp->helpMode' => [
                $description => 'Prefix inline help with (<section> - <key>).',
            ],
            'core->app->qform->stateHandler' => [
                $editable => false,
                $description => <<<TEXT
The QFormStateHandler to use to handle the actual serialized form.
By default, QFormStateHandler will be used (which simply outputs the entire serialized form data stream to the form), but file- and session- based, or any custom db-based FormState handling can be used as well.
TEXT
            ],
            'core->app->qform->fileFormStateHandler->fileNamePrefix' => [
                $description => <<<TEXT
Prefix for file name where the serialized form-state data is stored,
TEXT
            ],
            'core->app->qform->memcachedFormStateHandler->keyNamePrefix' => [
                $description => <<<TEXT
Prefix for key that addresses the serialized form-state data in memcached storage,
TEXT
            ],
            'core->app->removeWhitespace' => [
                $description => 'Remove whitespace characters from result html.',
            ],
            'core->app->seoFriendlyUrlEnabled' => [
                $description => 'Enable SEO friendly URL, SAM-2869.',
            ],
            'core->app->session->invalidIdReaction' => [
                $description => 'Different application reactions on php session id problem',
                $knownSet => Constants\PhpSession::$invalidIdReactions,
            ],
            'core->app->timezone->sortPriority' => [
                $knownSet => ['US', 'Africa', 'America', 'Asia', 'Atlantic', 'Australia', 'EU', 'Pacific'],
                $editComponent => Constants\Installation::ECOM_RADIO,
            ],
            'core->app->url->domainRule' => [
                $description => <<<TEXT
SAM-2944: Domains for links in email templates Options, can be combined in order of priority separated by comma
- SERVER_NAME use \$_SERVER['SERVER_NAME']
- HTTP_HOST use value from core->app->httpHost
- ACCOUNT_HOST, for core->portal->enabled only, will use the respective object's account sub domain or url_domain, depending on setting
TEXT
            ],
            //app END

            //auction START
            'core->auction->clerkingStyle->default' => [
                $description => 'Clerking style assigned by default to newly created Live or Hybrid auction',
                $knownSetNames => Constants\Auction::$clerkingStyleNames,
            ],
            'core->auction->closer->executionTime' => [
                $description => 'Auction closer script configuration.',
            ],
            'core->auction->dbCacheTimeout' => [
                $description => 'Lifetime for record in `auction_cache` table',
            ],
            'core->auction->dbCacheUpdateMaxExecutionTime' => [
                $description => 'SAM-2647 Max execution time for script, that updates auction_cache.',
            ],
            'core->auction->extendFromCurrentTime->enabled' => [
                $description => 'SAM-1945: Extend from current time.',
            ],
            'core->auction->hybrid->closingDelay' => [
                $description => 'Delay after "Extend Time" interval.',
            ],
            'core->auction->hybrid->extendTimeMin' => [
                $description => 'Minimal allowed "Extend Time" interval in seconds.',
            ],
            'core->auction->list->syncTimeout' => [
                $description => 'Data auto sync timeout in seconds.',
            ],
            'core->auction->saleNo->concatenated' => [
                $description => 'Concatenated or selected sale# input, SAM-3023.',
            ],
            'core->auction->test->prefix' => [
                $description => 'Test auction prefix. SAM-4105, SAM-840.',
            ],
            'core->auction->bidderNumAssignmentRetries' => [
                $description => 'SAM-5025.',
            ],
            //auction END

            //bidding START
            'core->bidding->buyNow->timed->restriction' => [
                $knownSetNames => Constants\Auction::BUY_NOW_TIMED_RESTRICTION_NAMES,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'Restriction strategy applied for Timed auction lot bidding and Buy Now feature, SAM-4264.'
                    . '\'CB\' 1.a: “Disable Buy Now function, if Current Bid reaches Buy Now amount”'
                    . '\'FB\' 1.b: “Disable Buy Now function, as soon as the first bid is placed”',
            ],
            'core->bidding->inlineConfirmTimeout' => [
                $description => 'Timeout in seconds for confirm place bid.',
            ],
            'core->bidding->highBidWarningMultiplier' => [
                $description => 'SAM-3502: Accidental high bid warning.',
            ],
            'core->bidding->gate->payPal->ipnRetries' => [
                $description => 'Number of retries when ipn call returns empty.',
            ],
            'core->bidding->gate->payPal->pause' => [
                $description => 'Time pause before the retry. Using microsecond. 500000 microseconds equivalent to 500 milliseconds.',
            ],
            'core->bidding->userAuthorization->expiration' => [
                $description => 'Number of days until this authorization/ capture expires.'
                    . ' -1: never expires. 0: expires immediately.',
            ],
            //bidding END

            //cache START
            'core->cache->filesystem->gzipLevel' => [
                $knownSet => Constants\FileCache::$gzipCompressionLevels,
                $editComponent => Constants\Installation::ECOM_RADIO,
            ],
            'core->cache->filesystem->ttl' => [
                $description => 'seconds, 24 hour.',
            ],
            'core->cache->filesystem->filenameTransformation' => [
                $description => 'The way how to transform the filename of cache data file',
                $knownSet => Constants\FileCache::$filenameTransformations,
            ],
            'core->cache->memory->adapter->options->memoryLimit' => [
                $description => 'when null - use ini_get(\'memory_limit\') / 2.',
            ],
            'core->cache->memory->adapter->options->ttl' => [
                $description => 'this value should be enough for web (it isn\'t used in rtbd).',
            ],
            'core->cache->memory->adapter->options->writable' => [
                $description => 'enables write in cache.',
            ],
            //cache END

            //captcha START
            'core->captcha->alternative->minReq' => [
                $description => 'Alternative captcha options',
            ],
            'core->captcha->alternative->time' => [
                $description => 'Alternative captcha options',
            ],
            'core->captcha->secretText' => [
                $description => '',
            ],
            'core->captcha->type' => [
                $knownSet => Constants\Captcha::$types,
                $editComponent => Constants\Installation::ECOM_RADIO,
            ],
            //captcha END

            //csv START
            'core->csv->clearValue' => [
                $description => 'SAM-4396',
            ],
            'core->csv->defaultValue' => [
                $description => 'SAM-4396',
            ],
            //csv END

            //db STARTt
            'core->db->adapter' => [
                $editable => false,
                $constraints => [
                    Constants\Installation::C_REQUIRED
                ],
            ],
            'core->db->server' => [
                $editable => false,
                $constraints => [
                    Constants\Installation::C_REQUIRED
                ],
            ],
            'core->db->port' => [
                $editable => false,
            ],
            'core->db->database' => [
                $editable => false,
                $constraints => [
                    Constants\Installation::C_REQUIRED
                ],
            ],
            'core->db->username' => [
                $editable => false,
                $constraints => [
                    Constants\Installation::C_REQUIRED
                ],
            ],
            'core->db->password' => [
                $visible => false,
                $editable => false,
                $constraints => [
                    Constants\Installation::C_REQUIRED
                ],
            ],
            'core->db->encoding' => [
                $editable => false,
                $knownSetNames => Constants\Mysql::$charsetsNames,
                $editComponent => Constants\Installation::ECOM_SELECT,
            ],
            'core->db->readonly->encoding' => [
                $knownSetNames => Constants\Mysql::$charsetsNames,
                $editComponent => Constants\Installation::ECOM_SELECT,
            ],
            //db END

            //debug START
            'core->debug->web->blockInvalid' => [
                $description => 'Stop application execution when web debug parameter checking failed',
            ],
            'core->debug->web->displayOnPage' => [
                $description => 'Render debug info right on page. Currently only: lot values at public Lot List pages.',
            ],
            'core->debug->web->enabled' => [
                $description => 'Turn on/off web debug feature',
            ],
            'core->debug->web->paramName' => [
                $description => 'Pass debug level in query string to enable web debugging',
            ],
            //debug END

            //custom START
            'core->custom->classSearchWay' => [
                $description => 'The way to search customized class: \'array\' - in core->custom->registry, \'file\' - search in file system (SAM-1921)',
            ],
            'core->custom->registry' => [
                $type => Constants\Type::T_ARRAY,
                $description => <<<TEXT
List of class names, that have customized class (SAM-1921)
Example: ['Sam\\Log\\Logger' => true]
TEXT
            ],
            //custom END

            //entity START
            'core->entity->futileSave->skipUpdate' => [
                $description => 'SAM-5102: Futile entity save check. true means that it should skip unmodified entity save operations'
                    . ', false means that it should save anyway',
            ],
            'core->entity->futileSave->log->enabled' => [
                $description => 'SAM-5102: Futile entity save check. Enable logging unmodified entity save case, when skipUpdate is on',
            ],
            //entity END

            //feed START
            'core->feed->customReplacements->order->lengthLimit' => [
                $description => 'column `order` in db is decimal(10,2)',
            ],
            //feed END

            //filesystem START
            'core->filesystem->permissions->file' => [
                $description => 'Linux access rights assigned to created files',
                $type => Constants\Type::T_STRING,
                $constraints => [
                    Constants\Installation::C_OCT_STRING,
                ],
            ],
            'core->filesystem->permissions->directory' => [
                $description => 'Linux access rights assigned to created directories',
                $type => Constants\Type::T_STRING,
                $constraints => [
                    Constants\Installation::C_OCT_STRING,
                ],
            ],
            'core->filesystem->permissions->thumbnailDirectory' => [
                $description => 'Linux access rights assigned to created directories for static files of resized images',
                $type => Constants\Type::T_STRING,
                $constraints => [
                    Constants\Installation::C_OCT_STRING,
                ],
            ],
            'core->filesystem->remote->masterHost' => [
                $description => 'HTTP_HOST should be used',
            ],
            'core->filesystem->remote->ipAllow' => [
                $type => Constants\Type::T_ARRAY,
                $constraints => [
                    Constants\Installation::C_SUBNET,
                ],
            ],
            'core->filesystem->remote->ipDeny' => [
                $type => Constants\Type::T_ARRAY,
                $constraints => [
                    Constants\Installation::C_SUBNET,
                ],
            ],
            'core->filesystem->remote->folderAllow' => [
                $type => Constants\Type::T_ARRAY,
            ],
            'core->filesystem->remote->regexDeny' => [
                $type => Constants\Type::T_ARRAY,
            ],
            //filesystem END

            //general START
            'core->general->olcRetry->disabledEntity' => [
                $type => Constants\Type::T_ARRAY,
            ],
            //general END

            //image START
            'core->image->cacheLifetime' => [
                $description => '1800',
            ],
            'core->image->encryptionKey' => [
                $constraints => [
                    Constants\Installation::C_REQUIRED,
                    Constants\Installation::C_HEX_STRING,
                ],
            ],
            'core->image->linkPrefix' => [
                $description => <<<TEXT
- using a caching proxy via a different domain just for images for faster load times (no cookies and browser limit of concurrent requests per domain improvement)
- mounting / syncing an external file system for static hosting, eg S3 that can be accessed over a different domain, but with the same paths.
TEXT,
                $type => Constants\Type::T_ARRAY,
                $editable => false,
            ],
            'core->image->maxWidthHeight' => [
                $description => '3000 * 2000',
            ],
            'core->image->uploadMaxSize' => [
                $description => 'Uploaded image size limit (KB)',
            ],
            'core->image->thumbnail->size0' => [
                $description => 'largest image',
            ],
            'core->image->thumbnail->size1' => [
                $description => 'featured lots on auction info page',
            ],
            'core->image->thumbnail->size2' => [
                $description => 'medium size image on detail page & bidding client',
            ],
            'core->image->thumbnail->size4' => [
                $description => 'thumbnail (catalog, details, other lots)',
            ],
            'core->image->thumbnail->size5' => [
                $description => 'clerking console',
            ],
            'core->image->thumbnail->size6' => [
                $description => 'search results',
            ],
            'core->image->thumbnail->size7' => [
                $description => 'header logo',
            ],
            'core->image->thumbnail->size8' => [
                $description => 'Large image on projector',
            ],
            //image END

            //install START
            //Installation level configuration management, SAM-4886
            'core->install->username' => [
                $description => 'Username for sign at this page. Installation level configuration management, SAM-4886',
                $editable => false,
            ],
            'core->install->password' => [
                $visible => false,
            ],
            'core->install->ipAllow' => [
                $description => <<<TEXT
Allowed IP whitelist. Set to ['0.0.0.0/0', '::/0'] for any ip.
SAM-4886: Installation level configuration management
TEXT
                ,
                $editable => false,
                $type => Constants\Type::T_ARRAY,
                $constraints => [
                    Constants\Installation::C_SUBNET,
                ],
            ],
            //install END

            //invoice START
            'core->invoice->autoInvoicing' => [
                $description => <<<TEXT
Lot won/purchase cases when auto-invoicing should be performed:
REGULAR - Regular bidding items will be auto-invoiced,
BUY_NOW - Buy now items will be auto-invoiced.
TEXT,
                $editComponent => Constants\Installation::ECOM_MULTISELECT,
                $knownSet => Constants\Invoice::AUTO_INVOICING_CASES,
                $type => Constants\Type::T_ARRAY,
            ],
            //invoice END

            //lot START
            'core->lot->bulkGroup->revokeRoleOnDelete' => [
                $description => 'When it is set to true, then lot soft-deleting should result to wiping out lot bulk grouping information from soft-deleted auction lot item (SAM-6697)',
                $editable => false,
            ],
            'core->lot->category->imageSize->width' => [
                $description => 'Category image dimensions',
            ],
            'core->lot->category->imageSize->height' => [
                $description => 'Category image dimensions',
            ],
            'core->lot->customField->postalCode->searchRadius' => [
                $type => Constants\Type::T_ARRAY,
                $knownSet => Constants\CustomField::$postalCodeRadiuses,
                $editComponent => Constants\Installation::ECOM_MULTISELECT,
                $description => 'miles',
            ],
            'core->lot->image->inBucketLimit' => [
                $description => 'Max quantity of files, that could be uploaded in the bucket',
            ],
            'core->lot->image->inBucketMaxSize' => [
                $description => 'Max file size for images uploaded through the bucket (in Bytes)',
            ],
            'core->lot->image->perLotLimit' => [
                $description => 'Max quantity of files, that could be assigned to a lot.',
            ],
            'core->lot->itemNo->concatenated' => [
                $description => 'Concatenated or separated item# input, SAM-3023.',
            ],
            'core->lot->lotNo->concatenated' => [
                $description => 'Concatenated or separated lot# input, SAM-3023.',
            ],
            'core->lot->seoUrl->dbCache->updateMaxExecutionTime' => [
                $description => 'SAM-3588 SEO URL and meta information improvements. Seconds.',
            ],
            'core->lot->name->lengthLimit' => [
                $description => 'Max size of lot_item.name.',
            ],
            'core->lot->descriptionOnHover->enabled' => [
                $description => 'turn rendering on/off. Lot description rendering options, when pointer hovers on lot (SAM-3816).',
            ],
            'core->lot->descriptionOnHover->length' => [
                $description => 'length limit of description. Lot description rendering options, when pointer hovers on lot (SAM-3816).',
            ],
            'core->lot->orderIgnoreWords' => [
                $type => Constants\Type::T_ARRAY,
                $description => 'Stop words, which should be ignored when ordering by custom field, SAM-1523',
            ],
            'core->lot->reserveNotice->enabled' => [
                $description => 'SAM-5045: Reserve met label for auctions.',
            ],
            'core->lot->video->enabled' => [
                $description => 'Youtube link on individual lot with help of lot custom field',
            ],
            //lot END

            //lotDetails START
            'core->lotDetails->otherLots->cacheControl->maxAge' => [
                $description => <<<TEXT
SAM-3506: Other lots on responsive lot detail page needs to be refactored. 
0 - means do not cache
TEXT
            ],
            'core->lotDetails->syncTimeout->live' => [
                $description => 'Data auto sync timeout. seconds',
            ],
            'core->lotDetails->syncTimeout->timed' => [
                $description => 'Data auto sync timeout. seconds',
            ],
            //lotDetails END

            //map START
            'core->mapp->bidpathApiKey' => [
                $description => 'secret key posted via HTTP_X_BIDPATH_API_KEY to trigger switches like hiding the captcha on signup, forgot password etc'
                    . ' when the page is requested from the mobile app',
            ],
            //map END

            //general START
            'core->general->debugLevel' => [
                $knownSetNames => Constants\Debug::$levelNames,
                $editComponent => Constants\Installation::ECOM_RADIO,
            ],
            //general END

            //path START
            'core->path' => [
                $visible => false,
            ],
            //path END

            //portal START
            'core->portal->domainAuctionVisibility' => [
                $knownSetNames => Constants\AccountVisibility::$typeNames,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'Account visibility (SAM-3051). Possible values : separate, directlink, transparent.',
            ],
            'core->portal->enabled' => [
                $description => 'Enable SAM Portal Features',
            ],
            'core->portal->enableAccountAdminSignup' => [
                $description => 'Allow to create new account on new user registration (SAM-3655)',
            ],
            'core->portal->mainAccountId' => [
                $editable => false,
                $description => 'Id of main account - account.id (SAM-4010).',
            ],
            'core->portal->urlHandling' => [
                $knownSet => Constants\PortalUrlHandling::$types,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'Whether to use subdomains (default) or maindomain for sam portal url handling',
            ],
            //portal END

            //reminder START
            'core->reminder->registration->interval' => [
                $description => 'once per day. Reminders are run by cron.',
            ],
            //reminder END

            //responsive START
            'core->responsive->breadcrumb->auction->titleType' => [
                $knownSet => Constants\ResponsiveBreadcrumb::AUCTION_TITLE_TYPE,
                $description => 'Auction title rendering options for breadcrumb (SAM-5240).',
            ],
            'core->responsive->breadcrumb->lot->titleType' => [
                $knownSet => Constants\ResponsiveBreadcrumb::LOT_TITLE_TYPE,
                $description => 'Lot title rendering options for breadcrumb (SAM-5240).',
            ],
            'core->responsive->user->username->maskRegExpIfEmail' => [
                $type => Constants\Type::T_ARRAY,
                $editComponent => Constants\Installation::ECOM_LINE,
                $description => 'Regular expression for masking username, when it is rendered at public site: \'/search pattern/\', \'replace pattern\' (SAM-10235)',
            ],
            //responsive END

            //rtb START
            'core->rtb->auctioneer->bidder->address->takeFrom' => [
                $knownSet => Constants\Rtb::$auctionBidderAddresses,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => <<<TEXT
Define the source entity where high bidder address should be pulled from and displayed at Auctioneer console: 'billing' - UserBilling entity, 'shipping' - UserShipping entity
TEXT
            ],
            'core->rtb->clerk->bidder->address->takeFrom' => [
                $knownSet => Constants\Rtb::$auctionBidderAddresses,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => <<<TEXT
Define the source entity where high bidder address should be pulled from and displayed at Clerk console: 'billing' - UserBilling entity, 'shipping' - UserShipping entity
TEXT
            ],
            'core->rtb->client->ipAllow' => [
                $type => Constants\Type::T_ARRAY,
                $constraints => [
                    Constants\Installation::C_SUBNET,
                ],
            ],
            'core->rtb->client->ipDeny' => [
                $type => Constants\Type::T_ARRAY,
                $constraints => [
                    Constants\Installation::C_SUBNET,
                ],
            ],
            'core->rtb->bidsBelowCurrent' => [
                $description => 'max 20.',
            ],
            'core->rtb->catalog->columnEstimate' => [
                $description => 'Show "Estimate" column.',
            ],
            'core->rtb->catalog->loadedPages' => [
                $description => 'Count of pages simultaneously loaded.',
            ],
            'core->rtb->catalog->pageLength' => [
                $description => 'Default length for rtb catalog page (lots in the page).',
            ],
            'core->rtb->contextMenuEnabled' => [
                $description => 'Enable/disable context menu (right mouse click) at admin clerk console.',
            ],
            'core->rtb->memoryCache->enabled' => [
                $description => 'Enables default memory caching mechanism in rtbd process.',
            ],
            'core->rtb->messageCenter->customMessageLimit' => [
                $description => 'Allowed count of user messages in the Message Center',
            ],
            'core->rtb->messageCenter->renderedMessageCount' => [
                $description => 'Count of rendered messages in chat screen.',
            ],
            'core->rtb->projector->numberRoundPrecision' => [
                $description => <<<TEXT
Define round precision of some numbers at round project (Current bid, Estimates).
-1 - to round to whole numbers (83 => 80)
TEXT
            ],
            'core->rtb->rtbdViewerRepeater->enabled' => [
                $description => 'SAM-3924: RTBD scaling by providing a "repeater/ broadcasting" service for viewers.',
            ],
            'core->rtb->server->bindPort' => [
                $type => Constants\Type::T_INTEGER,
            ],
            'core->rtb->server->wss' => [
                $description => 'Rtbd WebSocket connection schema (SAM-3889)',
                $knownSetNames => [
                    false => 'ws://',
                    true => 'wss://',
                ],
            ],
            'core->rtb->server->eventLoop' => [
                $description => 'Enable/disable event loop in rtbd process',
            ],
            'core->rtb->server->pool->discovery' => [
                $knownSetNames => Constants\RtbdPool::$discoveryStrategyNames
            ],
            'core->rtb->server->pool->instances' => [
                $type => Constants\Installation::T_STRUCT_ARRAY,
                $editComponent => Constants\Installation::ECOM_STRUCT_MULTILINE,
                $editable => false,
            ],
            'core->rtb->soundVolume' => [
                $description => 'Default volume for rtb sounds. This const used by sound manager lib.',
            ],
            //rtb END

            //search START
            'core->search->compactImageFormat' => [
                $knownSet => Constants\Responsive\AdvancedSearchConstants::$compactImageFormats,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'Catalog compact image format (icon, thumb).',
            ],
            'core->search->emptySearchQuery' => [
                $knownSet => Constants\Responsive\AdvancedSearchConstants::$emptySearchQueries,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => <<<TEXT
Settings for search landing page (SAM-3620)
default - default behaviour, run the search query without params.
no-query - do not execute a search query at all if no query parameters were passed.
TEXT
            ],
            'core->search->emptySearchQueryPanel' => [
                $knownSet => Constants\Responsive\AdvancedSearchConstants::$emptySearchQueryPanelStates,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => <<<TEXT
Search Panel options (SAM-3620)
closed - default value, search panel closed,
open - search panel is expanded and added css class .search_toggle on the parent div,
open-form - search panel is expanded and added css class .search_toggle and .open_search on the parent div, so it can be customized
TEXT
            ],
            'core->search->excludeClosedLots' => [
                $description => 'Default value for "Exclude closed lots" on search results (SAM-3390).',
            ],
            'core->search->index->type' => [
                $knownSetNames => Constants\Search::$indexTypeNames,
                $editComponent => Constants\Installation::ECOM_RADIO,
            ],
            'core->search->syncTimeout' => [
                $description => 'Data auto sync timeout. seconds',
            ],
            //search END

            //security START
            'core->security->ssl->enabled' => [
                $description => 'HTTPS connection'
            ],
            //security END

            //soap START
            'core->soap->emptyStringBehavior' => [
                $knownSetNames => Constants\Soap::$emptyStringBehaviorNames,
                $editComponent => Constants\Installation::ECOM_RADIO,
            ],
            'core->soap->ipAllow' => [
                $type => Constants\Type::T_ARRAY,
                $constraints => [
                    Constants\Installation::C_SUBNET,
                ],
            ],
            'core->soap->ipDeny' => [
                $type => Constants\Type::T_ARRAY,
                $constraints => [
                    Constants\Installation::C_SUBNET,
                ],
            ],
            'core->soap->clearValue' => [
                $description => 'SAM-4396',
            ],
            'core->soap->defaultValue' => [
                $description => 'SAM-4396',
            ],
            //soap END

            //sso START
            // tokenLink
            'core->sso->tokenLink->passphrase' => [
                $constraints => [
                    // required, when feature enabled
                    Constants\Installation::C_SPECIAL_COMPLEX => \Sam\Installation\Config\Edit\Validate\Value\Special\SsoTokenLink\Validator::class,
                ]
            ],
            'core->sso->tokenLink->cacheType' => [
                $knownSetNames => Constants\TokenLink::$cacheTypeNames,
            ],
            // OpenId
            'core->sso->openId->enabled' => [
                $description => 'Enable OpenId SSO feature',
            ],
            'core->sso->openId->responsive->signUp->enabled' => [
                $description => 'Allow user to sign up at public site through OpenId SSO. Be aware that first have to be enabled at Identity Provider`s side.',
            ],
            'core->sso->openId->responsive->profile->password->enabled' => [
                $description => 'Enable password management at Responsive Profile page',
            ],
            //sso END

            //tax START
            'core->tax->stackedTax->enabled' => [
                $description => 'Enable the "Stacked Tax" feature (SAM-10417)',
            ],
            //tax END

            //user START
            'core->user->bidderNumber->padLength' => [
                $description => <<<TEXT
Length of bidder# with padding.
Should not be modified during lifetime of application, because it affects the existing data state.
TEXT,
                $editable => false,
            ],
            'core->user->bidderNumber->padString' => [
                $description => <<<TEXT
Padding character for bidder#.
Should not be modified during lifetime of application, because it affects the existing data state.
TEXT,
                $knownSetNames => Constants\Bidder::PADDING_CHARACTER_NAMES,
                $editable => false,
            ],
            'core->user->buyerInterestPeriod' => [
                $description => 'SAM-2560: Bidonfusion - Track buyer interests',
            ],
            'core->user->credentials->generate->password->defaultLength' => [
                $description => 'This length password should be generated, when PW_MIN_LEN setting is dropped to "0"',
            ],
            'core->user->impersonate->cacher' => [
                $description => 'Cache adapter while impersonating authorized user',
                $knownSetNames => Constants\UserImpersonate::CACHE_ADAPTER_NAMES,
            ],
            'core->user->info->phoneType->default' => [
                $description => 'SAM-5614: Enable the User Info -> Phone Type\'s default value to be controlled via core config',
                $knownSetNames => Constants\User::ALL_PHONE_TYPE_NAMES,
            ],

            'core->user->logProfile->mode' => [
                $knownSetNames => Constants\UserLog::$modeDescriptions,
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'Log mode for user profile change (SAM-1444).',
            ],
            'core->user->logProfile->messageMaxLength' => [
                $description => 'Log message maximal allowed length',
            ],
            'core->user->phoneNumberFormat' => [
                $description => 'Phone number format (structured,simple)',
            ],
            'core->user->reseller->auctionBidderCertUploadDir' => [
                $editable => false,
            ],
            'core->user->reseller->userCertUploadDir' => [
                $editable => false,
            ],
            'core->user->signup->newsletter->enabled' => [
                $description => 'Default value for newsletter choice at SignUp page (SAM-5413)',
            ],
            'core->user->signup->verifyEmail->ttl' => [
                $description => 'Time to live of email verification code (SAM-6783)',
            ],
            'core->user->signup->verifyEmail->verificationCodeLength' => [
                $description => 'Email verification code length (SAM-6783)',
            ],
            'core->user->signup->verifyEmail->verificationSeed' => [
                $description => 'Email verification seed (SAM-6783)',
            ],
            'core->user->systemUsername' => [
                $description => 'Username of system user',
            ],
            //user END

            //sitemap START
            'core->sitemap->enableSingleIndex' => [
                $description => 'Display auctions from all accounts in single sitemap output, independently of account filtering',
            ],
            'core->sitemap->maxSizeOfFile' => [
                $description => 'Maximum sitemap/index file size (MB)',
            ],
            'core->sitemap->maxNumberOfUrls' => [
                $description => 'Maximum number of sitemap url or index url',
            ],
            //sitemap END

            //vendor START
            'core->vendor->artistResaleRights->currency' => [
                $description => 'Currency for ARR',
            ],
            'core->vendor->artistResaleRights->price' => [
                $description => 'Minimum hammer price for ARR',
            ],
            'core->vendor->artistResaleRights->tax' => [
                $description => 'Default ARR tax percent(%) on hammer price',
            ],
            'core->vendor->google->auth->credentials' => [
                $description => 'path to file with service account credentials',
            ],
            'core->vendor->google->analytics->webPropertyId' => [
                $description => 'SAM Google Analytics feature use tracking to use set \'UA-48963025-1\'',
            ],
            'core->vendor->google->analytics->trackingCode' => [
                $description => 'SAM use Google Analytics event tracking code for some form buttons(Signup,Login)',
            ],
            'core->vendor->magicZoomPlus->zoomMode' => [
                $knownSet => ['zoom', 'magnifier', 'preview', 'off'],
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'How to zoom image',
            ],
            'core->vendor->magicZoomPlus->zoomOn' => [
                $knownSet => ['hover', 'click'],
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'When activate zoom',
            ],
            'core->vendor->magicZoomPlus->zoomPosition' => [
                $knownSet => ['left', 'right', 'top', 'bottom', 'inner'],
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'Position of zoom window',
            ],
            'core->vendor->magicZoomPlus->zoomWidth' => [
                $description => <<<TEXT
Width of zoom window. 
Possible values: <percentage>, <pixels>, auto
TEXT
            ],
            'core->vendor->magicZoomPlus->zoomHeight' => [
                $description => <<<TEXT
Height of zoom window. 
Possible values: <percentage>, <pixels>, auto
TEXT
            ],
            'core->vendor->magicZoomPlus->zoomDistance' => [
                $description => <<<TEXT
Distance from small image to zoom window.
Possible values: <pixels>
TEXT
            ],
            'core->vendor->magicZoomPlus->zoomCaption' => [
                $knownSet => ['top', 'bottom', 'off'],
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'Position of caption on zoomed image',
            ],
            'core->vendor->magicZoomPlus->hint' => [
                $knownSet => ['once', 'always', 'off'],
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'How to show hint',
            ],
            'core->vendor->magicZoomPlus->variableZoom' => [
                $description => 'Whether to allow changing zoom ratio with mouse wheel',
            ],
            'core->vendor->magicZoomPlus->lazyZoom' => [
                $description => 'Whether to load large image on demand (on first activation)',
            ],
            'core->vendor->magicZoomPlus->smoothing' => [
                $description => 'Enable/disable smooth zoom movement',
            ],
            'core->vendor->magicZoomPlus->rightClick' => [
                $description => 'Whether to allow context menu on right click',
            ],
            'core->vendor->magicZoomPlus->upscale' => [
                $description => 'Whether to scale up the large image if its original size is not enough for a zoom effect',
            ],
            'core->vendor->magicZoomPlus->selectorTrigger' => [
                $knownSet => ['click', 'hover'],
                $editComponent => Constants\Installation::ECOM_RADIO,
                $description => 'Mouse event used to switch between multiple images',
            ],
            'core->vendor->magicZoomPlus->transitionEffect' => [
                $description => 'Whether to enable dissolve effect when switching between images',
            ],
            'core->vendor->magicZoomPlus->autostart' => [
                $description => 'Whether to start Zoom on image automatically on page load or manually',
            ],
            'core->vendor->magicZoomPlus->cssClass' => [
                $description => 'Extra CSS class(es) to apply to zoom instance',
            ],
            'core->vendor->samSharedService->postalCode->url' => [
                $description => 'Fetch from third-party service coordinates by postal code',
            ],
            'core->vendor->samSharedService->tax->loginToken' => [
                $description => 'Fetch from third-party service tax data by country and postal code.',
            ],
            //vendor END
        ];
    }
}
