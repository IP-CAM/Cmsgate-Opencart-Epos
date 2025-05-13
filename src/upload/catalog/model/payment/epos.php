<?php
namespace Opencart\Catalog\Model\Extension\CmsgateOpencartEpos\Payment;

use esas\cmsgate\opencart\ModelExtensionPayment;

require_once(dirname(__FILE__, 4) . '/system/library/esas/cmsgate/epos/init.php');

class Epos extends ModelExtensionPayment
{
    public function getMethods(array $address): array
    {
        return parent::getMethod($address, false);
    }
}