<?php
header('Content-Type: text/html; charset=utf-8');

use esas\cmsgate\epos\controllers\ControllerEposAddInvoice;
use esas\cmsgate\epos\controllers\ControllerEposCallback;
use esas\cmsgate\epos\controllers\ControllerEposCompletionPage;
use esas\cmsgate\opencart\CatalogControllerExtensionPayment;
use esas\cmsgate\epos\RegistryEposOpencart;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\view\ViewBuilderOpencart;
use esas\cmsgate\wrappers\SystemSettingsWrapperOpencart;

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/system/library/esas/cmsgate/epos/init.php');

class ControllerExtensionPaymentEpos extends CatalogControllerExtensionPayment
{
    public function index()
    {
        return parent::index();
    }

    /**
     * @param $data
     * @param $orderWrapper
     * @throws Throwable
     */
    protected function addPaySystemIndexData(&$data, $orderWrapper)
    {
        $data['confirmOrderForm'] = ViewBuilderOpencart::elementConfirmOrderForm($orderWrapper);
    }


    public function pay()
    {
        try {
            $orderId = $this->session->data['order_id'];
            if (!isset($orderId)) {
                $this->response->redirect(SystemSettingsWrapperOpencart::getInstance()->linkCatalogCheckout());
                return false;
            }
            $orderWrapper = RegistryEposOpencart::getRegistry()->getOrderWrapper($orderId);
            // проверяем, привязан ли к заказу extId, если да,
            // то счет не выставляем, а просто прорисовываем старницу
            if (empty($orderWrapper->getExtId())) {
                $controller = new ControllerEposAddInvoice();
                $controller->process($orderWrapper);
            }
            $controller = new ControllerEposCompletionPage();
            $completionPanel = $controller->process($orderId);
            $data['completionPanel'] = $completionPanel;
            $this->document->setTitle($this->language->get('heading_title'));
            $this->addCommon($data);
            $this->addCheckoutContinueButton($data);
            $this->response->setOutput(
                $this->load->view(
                    $this->getView("epos_checkout_success"), $data));

        } catch (Throwable $e) {
            return $this->redirectFailure("pay", $e);
        } catch (Exception $e) { // для совместимости с php 5
            return $this->redirectFailure("pay", $e);
        }
    }

    public function callback()
    {
        try {
            $controller = new ControllerEposCallback();
            $controller->process();
        } catch (Throwable $e) {
            Logger::getLogger("callback")->error("Exception:", $e);
        } catch (Exception $e) { // для совместимости с php 5
            Logger::getLogger("callback")->error("Exception:", $e);
        }
    }



}
