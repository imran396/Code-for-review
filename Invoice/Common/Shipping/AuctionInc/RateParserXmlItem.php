<?php
/**
 * SAM-4770 : Refactor Auction Inc modules
 * https://bidpath.atlassian.net/browse/SAM-4770
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           3/3/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\AuctionInc;

use XMLParser;

/**
 * Class AuctionInc
 * @package Sam\Invoice\Common\Shipping\RateParserXmlItem
 */
class RateParserXmlItem extends RateParserXml
{
    /**
     * @var int
     */
    public int $pkgItemCount;
    /**
     * @var int
     */
    public int $pkgCount;
    /**
     * @var int
     */
    public int $rateCount;
    /**
     * @var bool
     */
    public bool $debug;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->pkgItemCount = 0;
        $this->pkgCount = 0;
        $this->rateCount = 0;
        $this->debug = false;
        $this->init();
        return $this;
    }

    ### --------------------------------------------------------------------
    ### XML Parser Functions
    ### --------------------------------------------------------------------

    /**
     * Handle start element
     *
     * @access private
     * @param XMLParser $xp xml parser resource
     * @param string $name name of the element
     * @param array $attribs attributes
     * @return void
     */
    public function startHandler($xp, string $name, array $attribs): void
    {
        $this->currentTag = $name;
        $attribs = array_change_key_case($attribs, CASE_LOWER);

        if ($this->debug) {
            echo " + startHandler() : $name<br>";
        }

        if (
            $name === "ShipRate"
            && isset($this->dom['ShipRate'][$this->rateCount]["PackageDetail"])
            && is_array($this->dom['ShipRate'][$this->rateCount]["PackageDetail"][$this->pkgCount])
        ) {
            parent::startHandler($xp, $name, $attribs);
        } else {
            switch ($name) {
                case 'ItemShipRate':
                    $this->dom = [];
                    break;
                case 'ShipRateList':
                    xml_set_character_data_handler($this->parser, $this->cdataHandler_ShipRate(...));
                    $this->dom['ShipRate'] = [];
                    break;
                case 'ShipRate':
                    $this->dom['ShipRate'][$this->rateCount] = [];
                    break;
                case 'PackageDetail':
                    $this->pkgCount = 0;
                    break;
                case 'Package':
                    xml_set_character_data_handler($this->parser, $this->cdataHandler_Package(...));
                    $this->dom['ShipRate'][$this->rateCount]["PackageDetail"][$this->pkgCount] = [];
                    $this->pkgItemCount = 0;
                    break;
                case 'PkgItem':
                    xml_set_character_data_handler($this->parser, $this->cdataHandler_PkgItem(...));
                    $this->dom['ShipRate'][$this->rateCount]["PackageDetail"][$this->pkgCount]["PkgItem"][$this->pkgItemCount] = [];
                    break;
                default:
                    parent::startHandler($xp, $name, $attribs);
            }
        }
    }

    /**
     * Handle end element
     *
     * @access private
     * @param resource $xp xml parser resource
     * @param string $name name of the element
     * @return void
     */
    public function endHandler($xp, $name): void
    {
        $this->currentTag = null; // <<<<< WATCH THIS, cdataHandlers called 3 times: tag space and \n
        if ($this->debug) {
            echo " + endHandler() : $name<br>";
        }

        if ($name === "ShipRate" && isset($this->dom['ShipRate'][$this->rateCount]["PackageDetail"][$this->pkgCount])) {
            parent::endHandler($xp, $name);
        } else {
            switch ($name) {
                case 'ShipRateList':
                case 'ItemShipRate':
                    xml_set_character_data_handler($this->parser, $this->cdataHandler(...));
                    break;
                case 'ShipRate':
                    $this->rateCount++;
                    break;
                case 'PackageDetail':
                    xml_set_character_data_handler($this->parser, $this->cdataHandler_ShipRate(...));
                    break;
                case 'Package':
                    $this->pkgCount++;
                    break;
                case 'PkgItem':
                    $this->pkgItemCount++;
                    break;
                default:
                    parent::endHandler($xp, $name);
            }
        }
    }

    //////////////////////////////////////////////////////////////////////////
    // Custom CDATA Handlers
    //////////////////////////////////////////////////////////////////////////

    /**
     * Handle character data within SHIPRATE element
     *
     * @access private
     * @param resource $xp xml parser resource
     * @param string $val value of the resource
     * @return void
     */
    public function cdataHandler_ShipRate($xp, $val): void
    {
        static $map;
        if ($this->errState || empty($this->currentTag)) {
            return;
        }
        if (!isset($map)) {
            $map = [
                'valid' => 'Valid',
                'carriercode' => 'CarrierCode',
                'servicecode' => 'ServiceCode',
                'servicename' => 'ServiceName',
                'calcmethod' => 'CalcMethod',
                'rate' => 'Rate',
                'carrierrate' => 'CarrierRate',
                'surcharges' => 'Surcharges',
                'fuelsurcharges' => 'FuelSurcharges',
                'handlingfees' => 'HandlingFees',
                'declaredvalue' => 'DeclaredValue',
                'insurancecharges' => 'InsuranceCharges',
                'weight' => 'Weight',
                'packagecount' => 'PackageCount',
                'flatratecode' => 'FlatRateCode'
            ];
        }
        $this->cdataAdapter($val, $map, $this->rateCount, $this->dom['ShipRate']);
    }

    /**
     * Handle character data within PACKAGE element
     *
     * @access private
     * @param resource $xp xml parser resource
     * @param string $val value of the resource
     * @return void
     */
    public function cdataHandler_Package($xp, $val): void
    {
        static $map;
        if ($this->errState || empty($this->currentTag)) {
            return;
        }
        if (!isset($map)) {
            $map = [
                'quantity' => 'Quantity',
                'packmethod' => 'PackMethod',
                'origin' => 'Origin',
                'declaredvalue' => 'DeclaredValue',
                'weight' => 'Weight',
                'length' => 'Length',
                'width' => 'Width',
                'height' => 'Height',
                'oversizecode' => 'OversizeCode',
                'splitcode' => 'SplitCode',
                'flatratecode' => 'FlatRateCode',
                'carrierrate' => 'CarrierRate',
                'surcharge' => 'Surcharge',
                'fuelsurcharge' => 'FuelSurcharge',
                'insurance' => 'Insurance',
                'handling' => 'Handling',
                'shiprate' => 'ShipRate'
            ];
        }
        $this->cdataAdapter($val, $map, $this->pkgCount, $this->dom['ShipRate'][$this->rateCount]["PackageDetail"]);
    }

    /**
     * Handle character data within PKGITEM element
     *
     * @access private
     * @param resource $xp xml parser resource
     * @param string $val value of the resource
     * @return void
     */
    public function cdataHandler_PkgItem($xp, $val): void
    {
        static $map;
        if ($this->errState || empty($this->currentTag)) {
            return;
        }
        if (!isset($map)) {
            $map = [
                'refcode' => 'RefCode',
                'qty' => 'Qty',
                'weight' => 'Weight'
            ];
        }
        $this->cdataAdapter($val, $map, $this->pkgItemCount, $this->dom['ShipRate'][$this->rateCount]["PackageDetail"][$this->pkgCount]["PkgItem"]);
    }

    /**
     * Handle character data within ItemShipRate element
     *
     * @access private
     * @param resource $xp xml parser resource
     * @param string $val value of the resource
     * @return void
     */
    public function cdataHandler($xp, $val): void
    {
        if ($this->errState || empty($this->currentTag)) {
            return;
        }
        $map = ['currency' => 'Currency'];
        $this->cdataAdapter($val, $map, null, $this->dom);
    }
}
