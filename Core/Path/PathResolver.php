<?php
/**
 * SAM-5853: Project path resolver
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           26/2/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Path;

use Sam\Core\Application\Ui\Ui;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class PathProvider
 * @package
 */
class PathResolver extends CustomizableClass
{
    public const APP = '/app';
    public const BIN = '/bin';
    public const CACHE = '/var/tmp/cache';
    public const CLASSES = '/includes/classes';
    public const CONFIGURATION = '/_configuration';
    public const CONTROLLER_TPL = '/app/%s/controllers';
    public const CUSTOM_DIR = '/custom';
    public const DAEMON = '/bin/daemon';
    public const DATA_CLASS = '/includes/data_classes';
    public const DATA_CLASS_GEN = '/includes/data_classes/generated';
    public const DEFAULT_AUCTION_IMAGES = '/images/auction';
    public const DEFAULT_LOT_IMAGES = '/images/lot/0';
    public const DEFAULT_ACCOUNT_IMAGES = '/images/settings';
    public const DOCROOT = '/wwwroot';
    public const DOCROOT_RESELLER = '/wwwroot/reseller_data';
    public const DRAFT_TPL = '/app/%s/views/drafts';
    public const DYNAMIC_LANGUAGE = '/var/language';
    public const CUSTOM_FIELD_TRANSLATION_MASTER = '/var/language/master';
    public const FONT = '/resources/font';
    public const FUNCTIONAL_TEST = '/test/functional';
    public const FUNCTIONAL_TEST_TEMPORARY = '/var/tmp/functional-test';
    public const INCLUDES = '/includes';
    public const INLINE_HELP = '/resources/inline_help';
    public const LAYOUT_TPL = '/app/%s/views/layouts';
    public const LIBS = '/includes/libs';
    public const ZEND_FRAMEWORK_1 = '/vendor/shardj/zf1-future/library';
    public const VAR = '/var';
    public const LOG = '/var/logs';
    public const LOG_REPORT = '/var/logs/error_log';
    public const LOG_RUN = '/var/logs/run';
    public const PUBLIC_ASSETS_BASE_IMAGE = '/public/assets/base/image';
    public const QCODO_CORE = '/includes/libs/qcodo/_core';
    public const QCODO_DEV_TOOLS_CLI = '/src/qcodo/devtools';
    public const QCODO_DOCROOT_CSS_ASSETS = '/css';
    public const QCODO_DOCROOT_IMAGE_ASSETS = '/images';
    public const QCODO_DOCROOT_JS_ASSETS = '/assets/js/vendor';
    public const QCODO_DOCROOT_PHP_ASSETS = '/php';
    public const QCODO_LIB = '/includes/libs/qcodo';
    public const RESOURCES = '/_resources';
    public const SESSION = '/var/tmp/session';
    public const SOUND = '/resources/sound';
    public const TEMPORARY = '/var/tmp';
    public const TEXT_IMAGES = '/images/text_images';
    public const TRANSLATION = '/var/upload/language';
    public const TRANSLATION_MASTER = '/resources/language/master';
    public const UNIT_TEST = '/test/unit';
    public const UNIT_TEST_TEMPORARY = '/var/tmp/unit-test';
    public const UPLOAD = '/var/upload';
    public const UPLOAD_AUCTION_CUSTOM_FIELD_FILE = '/var/upload/auction_files';
    public const UPLOAD_AUCTION_IMAGE = '/var/upload/auction';
    public const UPLOAD_LOT_CATEGORY_IMAGE = '/var/upload/lot_category';
    public const UPLOAD_LOT_CUSTOM_FIELD_FILE = '/var/upload/item_files';
    public const UPLOAD_LOT_IMAGE_BUCKET = '/var/upload/lot_image_bucket';
    public const UPLOAD_LOT_ITEM_IMAGE = '/var/upload/lot_item';
    public const UPLOAD_RESELLER = '/var/upload/reseller';
    public const UPLOAD_SETTING = '/var/upload/settings';
    public const UPLOAD_USER_CUSTOM_FIELD_FILE = '/var/upload/user_files';
    public const VIEW_SCRIPT_TPL = '/app/%s/views/scripts';

    public const OP_MAIN_ACCOUNT_ID = OptionalKeyConstants::KEY_MAIN_ACCOUNT_ID; // int
    public const OP_IMAGE_LINK_PREFIXES = OptionalKeyConstants::KEY_IMAGE_LINK_PREFIXES; // string[]

    protected string $sysRoot;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Should be constructed with system root absolute path
     * @param string|null $sysRoot
     * @return PathResolver
     */
    public function construct(?string $sysRoot = null): PathResolver
    {
        $this->sysRoot = $sysRoot ?? dirname(__DIR__, 5);
        return $this;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function sysRoot(bool $isCustom = false): string
    {
        $customDir = $isCustom ? $this->customDir() : '';
        return $this->sysRoot . $customDir;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function app(bool $isCustom = false): string
    {
        return $this->build(self::APP, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function bin(bool $isCustom = false): string
    {
        return $this->build(self::BIN, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function cache(bool $isCustom = false): string
    {
        return $this->build(self::CACHE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function classes(bool $isCustom = false): string
    {
        return $this->build(self::CLASSES, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function configuration(bool $isCustom = false): string
    {
        return $this->build(self::CONFIGURATION, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function daemon(bool $isCustom = false): string
    {
        return $this->build(self::DAEMON, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function dataClass(bool $isCustom = false): string
    {
        return $this->build(self::DATA_CLASS, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function dataClassGenerated(bool $isCustom = false): string
    {
        return $this->build(self::DATA_CLASS_GEN, $isCustom);
    }

    /**
     * @param string $filename
     * @param bool $isCustom
     * @return string
     */
    public function dataClassGeneratedFile(string $filename, bool $isCustom = false): string
    {
        return $this->dataClassGenerated($isCustom) . '/' . $filename;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function docRoot(bool $isCustom = false): string
    {
        return $this->build(self::DOCROOT, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function docRootReseller(bool $isCustom = false): string
    {
        return $this->build(self::DOCROOT_RESELLER, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function font(bool $isCustom = false): string
    {
        return $this->build(self::FONT, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function functionalTest(bool $isCustom = false): string
    {
        return $this->build(self::FUNCTIONAL_TEST, $isCustom);
    }

    /**
     * @return string
     */
    public function functionalTestTemporary(): string
    {
        return $this->build(self::FUNCTIONAL_TEST_TEMPORARY);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function includes(bool $isCustom = false): string
    {
        return $this->build(self::INCLUDES, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function inlineHelp(bool $isCustom = false): string
    {
        return $this->build(self::INLINE_HELP, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function libs(bool $isCustom = false): string
    {
        return $this->build(self::LIBS, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function zf1(bool $isCustom = false): string
    {
        return $this->build(self::ZEND_FRAMEWORK_1, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function log(bool $isCustom = false): string
    {
        return $this->build(self::LOG, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function var(bool $isCustom = false): string
    {
        return $this->build(self::VAR, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function logReport(bool $isCustom = false): string
    {
        return $this->build(self::LOG_REPORT, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function logRun(bool $isCustom = false): string
    {
        return $this->build(self::LOG_RUN, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function session(bool $isCustom = false): string
    {
        return $this->build(self::SESSION, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoLib(bool $isCustom = false): string
    {
        return $this->build(self::QCODO_LIB, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoCore(bool $isCustom = false): string
    {
        return $this->build(self::QCODO_CORE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoDevToolsCli(bool $isCustom = false): string
    {
        return $this->build(self::QCODO_DEV_TOOLS_CLI, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoJsAssets(bool $isCustom = false): string
    {
        return $this->build(self::DOCROOT . self::QCODO_DOCROOT_JS_ASSETS, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoCssAssets(bool $isCustom = false): string
    {
        return $this->build(self::DOCROOT . self::QCODO_DOCROOT_CSS_ASSETS, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoImageAssets(bool $isCustom = false): string
    {
        $out = $this->build(self::DOCROOT . self::QCODO_DOCROOT_IMAGE_ASSETS, $isCustom);
        return $out;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function qcodoPhpAssets(bool $isCustom = false): string
    {
        return $this->build(self::DOCROOT . self::QCODO_DOCROOT_PHP_ASSETS, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function textImages(bool $isCustom = false): string
    {
        $out = $this->build(self::DOCROOT . self::TEXT_IMAGES, $isCustom);
        return $out;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function resources(bool $isCustom = false): string
    {
        return $this->build(self::RESOURCES, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function temporary(bool $isCustom = false): string
    {
        return $this->build(self::TEMPORARY, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function translation(bool $isCustom = false): string
    {
        return $this->build(self::TRANSLATION, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function translationMaster(bool $isCustom = false): string
    {
        return $this->build(self::TRANSLATION_MASTER, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function dynamicLanguage(bool $isCustom = false): string
    {
        return $this->build(self::DYNAMIC_LANGUAGE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function customFieldTranslationMaster(bool $isCustom = false): string
    {
        return $this->build(self::CUSTOM_FIELD_TRANSLATION_MASTER, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function unitTest(bool $isCustom = false): string
    {
        return $this->build(self::UNIT_TEST, $isCustom);
    }

    /**
     * @return string
     */
    public function unitTestTemporary(): string
    {
        return $this->build(self::UNIT_TEST_TEMPORARY);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function upload(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadLotItemImage(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_LOT_ITEM_IMAGE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadLotImageBucket(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_LOT_IMAGE_BUCKET, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadAuctionImage(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_AUCTION_IMAGE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadSetting(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_SETTING, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function sound(bool $isCustom = false): string
    {
        return $this->build(self::SOUND, $isCustom);
    }


    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadLotCustomFieldFile(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_LOT_CUSTOM_FIELD_FILE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadAuctionCustomFieldFile(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_AUCTION_CUSTOM_FIELD_FILE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadUserCustomFieldFile(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_USER_CUSTOM_FIELD_FILE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadLotCategoryImage(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_LOT_CATEGORY_IMAGE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function uploadReseller(bool $isCustom = false): string
    {
        return $this->build(self::UPLOAD_RESELLER, $isCustom);
    }

    // -----

    /**
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    public function controller(Ui $ui, bool $isCustom = false): string
    {
        return $this->buildTemplate(self::CONTROLLER_TPL, $ui, $isCustom);
    }

    /**
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    public function layout(Ui $ui, bool $isCustom = false): string
    {
        return $this->buildTemplate(self::LAYOUT_TPL, $ui, $isCustom);
    }

    /**
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    public function draft(Ui $ui, bool $isCustom = false): string
    {
        return $this->buildTemplate(self::DRAFT_TPL, $ui, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function draftResponsive(bool $isCustom = false): string
    {
        return $this->draft(Ui::new()->constructWebResponsive(), $isCustom);
    }

    /**
     * @param string $filename
     * @param bool $isCustom
     * @return string
     */
    public function draftResponsiveFile(string $filename, bool $isCustom = false): string
    {
        return $this->draftResponsive($isCustom) . '/' . $filename;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function draftAdmin(bool $isCustom = false): string
    {
        return $this->draft(Ui::new()->constructWebAdmin(), $isCustom);
    }

    /**
     * @param string $filename
     * @param bool $isCustom
     * @return string
     */
    public function draftAdminFile(string $filename, bool $isCustom = false): string
    {
        return $this->draftAdmin($isCustom) . '/' . $filename;
    }

    /**
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    public function viewScript(Ui $ui, bool $isCustom = false): string
    {
        return $this->buildTemplate(self::VIEW_SCRIPT_TPL, $ui, $isCustom);
    }

    /**
     * @param string $path
     * @param bool $isCustom
     * @return string
     */
    protected function build(string $path, bool $isCustom = false): string
    {
        return $this->sysRoot($isCustom) . $path;
    }

    /**
     * @param string $template
     * @param Ui $ui
     * @param bool $isCustom
     * @return string
     */
    protected function buildTemplate(string $template, Ui $ui, bool $isCustom = false): string
    {
        $controllerPath = sprintf($template, $ui->dir());
        return $this->build($controllerPath, $isCustom);
    }

    /**
     * @return string
     */
    protected function customDir(): string
    {
        return self::CUSTOM_DIR;
    }

    /**
     * set the image prefix for this account
     * SAM-6695: Image link prefix detection do not provide default value
     * @param int|null $accountId
     * @param array $optionals = [
     *     self::OP_MAIN_ACCOUNT_ID => int,
     *     self::OP_IMAGE_LINK_PREFIXES => array,
     * ]
     * @return string
     */
    public function imagePrefix(?int $accountId = null, array $optionals = []): string
    {
        // Fine tune with optionals
        $cfg = ConfigRepository::getInstance();
        if (!$accountId) {
            $accountId = (int)($optionals[self::OP_MAIN_ACCOUNT_ID]
                ?? $cfg->get('core->portal->mainAccountId'));
        }
        $linkPrefixes = (array)($optionals[self::OP_IMAGE_LINK_PREFIXES]
            ?? $cfg->get('core->image->linkPrefix')->toArray());

        // Perform calculations
        $imageLinkPrefix = '';
        if (isset($linkPrefixes[$accountId])) {
            $imageLinkPrefix = (string)$linkPrefixes[$accountId];
        } else {
            $defaultKey = Constants\Image::LP_DEFAULT;
            if (isset($linkPrefixes[$defaultKey])) {
                $imageLinkPrefix = (string)$linkPrefixes[$defaultKey];
            }
        }
        return $imageLinkPrefix;
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function baseImage(bool $isCustom = false): string
    {
        return $this->build(self::PUBLIC_ASSETS_BASE_IMAGE, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function defaultAuctionImages(bool $isCustom = false): string
    {
        return $this->build(self::DOCROOT . self::DEFAULT_AUCTION_IMAGES, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function defaultLotImages(bool $isCustom = false): string
    {
        return $this->build(self::DOCROOT . self::DEFAULT_LOT_IMAGES, $isCustom);
    }

    /**
     * @param bool $isCustom
     * @return string
     */
    public function defaultAccountImages(bool $isCustom = false): string
    {
        return $this->build(self::DOCROOT . self::DEFAULT_ACCOUNT_IMAGES, $isCustom);
    }
}
