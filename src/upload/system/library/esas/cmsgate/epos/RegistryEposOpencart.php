<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 11:22
 */

namespace esas\cmsgate\epos;

use esas\cmsgate\CmsConnectorOpencart;
use esas\cmsgate\epos\utils\RequestParamsEpos;
use esas\cmsgate\epos\view\admin\ManagedFieldsEpos;
use esas\cmsgate\view\admin\ConfigFormOpencart;
use esas\cmsgate\epos\view\client\CompletionPanelEposOpencart;
use esas\cmsgate\wrappers\SystemSettingsWrapperOpencart;

class RegistryEposOpencart extends RegistryEpos
{
    private $opencartRegistry;

    /**
     * RegistryOpencart constructor.
     * @param $opencartRegistry
     */
    public function __construct($opencartRegistry)
    {
        $this->opencartRegistry = $opencartRegistry;
        $this->cmsConnector = new CmsConnectorOpencart($opencartRegistry);
        $this->paysystemConnector = new PaysystemConnectorEpos();
    }

    /**
     * Переопделение для упрощения типизации
     * @return RegistryEposOpencart
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * Переопделение для упрощения типизации
     * @return ConfigFormOpencart
     */
    public function getConfigForm()
    {
        return parent::getConfigForm();
    }

    /**
     * @return SystemSettingsWrapperOpencart
     */
    public function getSystemSettingsWrapper()
    {
        return parent::getSystemSettingsWrapper();
    }

    public function createConfigForm()
    {
        $managedFields = new ManagedFieldsEpos();
        $managedFields->addAllExcept([
            ConfigFieldsEpos::shopName()]);
        return $this->cmsConnector->createCommonConfigForm($managedFields);
    }

    public function getCompletionPanel($orderWrapper)
    {
        $completionPanel = new CompletionPanelEposOpencart($orderWrapper);
        return $completionPanel;
    }

    /**
     * @return mixed
     */
    public function getOpencartRegistry()
    {
        return $this->opencartRegistry;
    }

    function getUrlWebpay($orderId)
    {
        $orderWrapper = RegistryEposOpencart::getRegistry()->getOrderWrapper($orderId);
        return SystemSettingsWrapperOpencart::getInstance()->linkCatalogExtension("pay")
            . "&" . RequestParamsEpos::ORDER_NUMBER . "=" . $orderWrapper->getOrderNumber();
    }

}