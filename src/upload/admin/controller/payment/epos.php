<?php

use esas\cmsgate\opencart\AdminControllerExtensionPayment;

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/system/library/esas/cmsgate/epos/init.php');

class ControllerPaymentEpos extends AdminControllerExtensionPayment
{
    public function index()
    {
        parent::index();
    }
}