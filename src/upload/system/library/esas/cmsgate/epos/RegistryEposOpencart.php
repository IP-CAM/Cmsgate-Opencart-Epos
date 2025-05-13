<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 15.07.2019
 * Time: 11:22
 */

namespace esas\cmsgate\epos;

use esas\cmsgate\CmsConnectorOpencart;
use esas\cmsgate\descriptors\ModuleDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\epos\utils\RequestParamsEpos;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\admin\ConfigFormOpencart;
use esas\cmsgate\epos\view\client\CompletionPanelEposOpencart;
use esas\cmsgate\epos\hro\client\CompletionPanelEposHRO;
use esas\cmsgate\hro\HROManager;
use esas\cmsgate\wrappers\SystemSettingsWrapperOpencart;

class RegistryEposOpencart extends RegistryEpos
{
    private $opencartRegistry;

    /**
     * RegistryOpencart constructor.
     */
    public function __construct()
    {
        $this->cmsConnector = new CmsConnectorOpencart();
        $this->paysystemConnector = new PaysystemConnectorEpos();
    }

    public function init()
    {
        parent::init();
        HROManager::fromRegistry()->addImplementation(CompletionPanelEposHRO::class, CompletionPanelEposOpencart::class);
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
        $managedFields = $this->getManagedFieldsFactory()->getManagedFieldsExcept(AdminViewFields::CONFIG_FORM_COMMON, [
            ConfigFieldsEpos::paymentMethodNameWebpay(),
            ConfigFieldsEpos::paymentMethodDetailsWebpay(),
            ConfigFieldsEpos::shopName(),
            ConfigFieldsEpos::useOrderNumber()]);
        return $this->cmsConnector->createCommonConfigForm($managedFields);
    }

    public function getCompletionPanel($orderWrapper)
    {
        $completionPanel = new CompletionPanelEposOpencart($orderWrapper);
        return $completionPanel;
    }

    function getUrlWebpay($orderWrapper)
    {
        return SystemSettingsWrapperOpencart::getInstance()->linkCatalogExtension("pay")
            . "&" . RequestParamsEpos::ORDER_NUMBER . "=" . $orderWrapper->getOrderNumber();
    }

    public function createModuleDescriptor()
    {
        return new ModuleDescriptor(
            "esas_epos",
            new VersionDescriptor("1.14.0", "2021-12-15"),
            "Прием платежей через ЕРИП (сервис EPOS)",
            "https://bitbucket.esas.by/projects/CG/repos/cmsgate-opencart-epos/browse",
            VendorDescriptor::esas(),
            "Выставление пользовательских счетов в ЕРИП"
        );
    }
}