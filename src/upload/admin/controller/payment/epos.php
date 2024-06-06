<?php
namespace Opencart\Admin\Controller\Extension\CmsgateOpencartEpos\Payment;

use esas\cmsgate\opencart\AdminControllerExtensionPayment;

require_once(dirname(__FILE__, 4) . '/system/library/esas/cmsgate/epos/init.php');

class Epos extends AdminControllerExtensionPayment
{
    public function index()
    {
        parent::index();
    }
}