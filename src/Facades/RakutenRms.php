<?php
/**
 * RakutenRms Facade
 *
 * @author wareon <wareon@qq.com>
 * @date 2020/1/10 12:30
 * @since rakuten rms 1.0
 */

namespace Wareon\RakutenRms\Facades;
use Illuminate\Support\Facades\Facade;

/**
 * @method static categroiesGet()
 * @method static categroyGet()
 * @method static categoryInsert($data)
 * @method static categoryUpdate($data)
 * @method static categoryDelete($data)
 *
 * @method static itemSearch(array $params)
 * @method static getItem(string $itemUrl)
 * @method static itemInsert(array $itemData)
 * @method static itemUpdate(array $itemData)
 * @method static itemDelete(array $itemData)
 *
 * @method static getReplaceUrl(string $url)
 *
 * @method static searchOrder(array $params)
 * @method static getOrder(array $params)
 * @method static confirmOrder(array $params)
 * @method static updateOrderShipping(array $params)
 * @method static updateOrderShippingAsync(array $params)
 * @method static getResultUpdateOrderShippingAsync(array $params)
 * @method static getSubStatusList(array $params)
 * @method static updateOrderSubStatus(array $params)
 * @method static updateOrderMemo(array $params)
 * @method static updateOrderRemarks(array $params)
 * @method static updateOrderSender(array $params)
 * @method static updateOrderSenderAfterShipping(array $params)
 * @method static cancelOrder(array $params)
 * @method static cancelOrderAfterShipping(array $params)
 * @method static updateOrderOrderer(array $params)
 * @method static updateOrderDelivery(array $params)
 * @method static getPayment(array $params)
 */

class RakutenRms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'RakutenRms';
    }
}
