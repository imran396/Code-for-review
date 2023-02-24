<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: admin_place_bid.proto

namespace Sam\Application\Controller\Admin\PlaceBid\Generated\Message;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>sam.admin.place_bid.PlaceBidResponse</code>
 */
class PlaceBidResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>bool success = 1;</code>
     */
    protected $success = false;
    /**
     * Generated from protobuf field <code>.sam.common.ResponseMessageContainer messages = 2;</code>
     */
    protected $messages = null;
    /**
     * Generated from protobuf field <code>.sam.auction_lot.admin_sync.AuctionLotData auctionLot = 3;</code>
     */
    protected $auctionLot = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type bool $success
     *     @type \Sam\Application\Controller\Base\Generated\ResponseMessageContainer $messages
     *     @type \Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\AuctionLotData $auctionLot
     * }
     */
    public function __construct($data = NULL) {
        \Sam\Application\Controller\Admin\PlaceBid\Generated\Message\Internal\Metadata\AdminPlaceBid::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>bool success = 1;</code>
     * @return bool
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Generated from protobuf field <code>bool success = 1;</code>
     * @param bool $var
     * @return $this
     */
    public function setSuccess($var)
    {
        GPBUtil::checkBool($var);
        $this->success = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.sam.common.ResponseMessageContainer messages = 2;</code>
     * @return \Sam\Application\Controller\Base\Generated\ResponseMessageContainer|null
     */
    public function getMessages()
    {
        return $this->messages;
    }

    public function hasMessages()
    {
        return isset($this->messages);
    }

    public function clearMessages()
    {
        unset($this->messages);
    }

    /**
     * Generated from protobuf field <code>.sam.common.ResponseMessageContainer messages = 2;</code>
     * @param \Sam\Application\Controller\Base\Generated\ResponseMessageContainer $var
     * @return $this
     */
    public function setMessages($var)
    {
        GPBUtil::checkMessage($var, \Sam\Application\Controller\Base\Generated\ResponseMessageContainer::class);
        $this->messages = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.sam.auction_lot.admin_sync.AuctionLotData auctionLot = 3;</code>
     * @return \Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\AuctionLotData|null
     */
    public function getAuctionLot()
    {
        return $this->auctionLot;
    }

    public function hasAuctionLot()
    {
        return isset($this->auctionLot);
    }

    public function clearAuctionLot()
    {
        unset($this->auctionLot);
    }

    /**
     * Generated from protobuf field <code>.sam.auction_lot.admin_sync.AuctionLotData auctionLot = 3;</code>
     * @param \Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\AuctionLotData $var
     * @return $this
     */
    public function setAuctionLot($var)
    {
        GPBUtil::checkMessage($var, \Sam\AuctionLot\Sync\Response\Concrete\AdminData\Generated\Message\AuctionLotData::class);
        $this->auctionLot = $var;

        return $this;
    }

}

