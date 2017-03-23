<?php
/**
 * Created by PhpStorm.
 * User: nacho.valera
 */
class Devopensource_ProductAddons_Model_Observer
{
    public function addProductAddonsButton($observer)
    {
        if(Mage::getStoreConfig('productaddons/general/backend_button', Mage::app()->getStore())){
            $_block = $observer->getBlock();
            $_type = $_block->getType();
            if($_type == 'adminhtml/catalog_product_edit') {
                $_deleteButton = $_block->getChild('delete_button');
                $_block->setChild('product_view_button',
                    $_block->getLayout()->createBlock('devopensource_productaddons/adminhtml_widget_button')
                );
                $_deleteButton->setBeforeHtml($_block->getChild('product_view_button')->toHtml());
            }
        }
    }

    public function addProductAddonsButtonFrontend($observer)
    {
        if(Mage::getStoreConfig('productaddons/general/frontend_button', Mage::app()->getStore())){
            if($this->isAdminLogged()) {
                $productId = $observer->getEvent()->getProduct()->getData()['entity_id'];
                $secondUrl = Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit/id/'.$productId.'/');
                echo "<button type='button' onclick=\"window.open('" . $secondUrl ."');\">View in backend</button>";
            }
        }
    }

    protected function isAdminLogged()
    {
        if(array_key_exists('adminhtml', $_COOKIE)){
            //get session path and add dir seperator and content field of cookie as data name with magento "sess_" prefix
            $sessionFilePath = Mage::getBaseDir('session').DS.'sess_'.$_COOKIE['adminhtml'];
            //write content of file in var
            $sessionFile = file_get_contents($sessionFilePath);
            //save old session
            $oldSession = $_SESSION;
            //decode adminhtml session
            session_decode($sessionFile);
            //save session data from $_SESSION
            $adminSessionData = $_SESSION;
            //set old session back to current session
            $_SESSION = $oldSession;
            if(array_key_exists('user', $adminSessionData['admin'])){
                //save Mage_Admin_Model_User object in var
                //$adminUserObj = $adminSessionData['admin']['user'];
                return true;
            } else {
                return false;
            }
        }
    }

}