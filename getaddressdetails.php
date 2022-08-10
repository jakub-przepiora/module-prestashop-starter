<?php

if (!defined('_PS_VERSION_'))
    exit();

class Getaddressdetails extends Module
{

    // DB prefix
    public $db_prefix = _DB_PREFIX_;


    public function __construct()
    {
        $this->name = 'getaddressdetails';
        $this->version = '1.0.0';
        $this->author = 'Jakub Przepióra';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('My module', $this->name );
        $this->description = $this->l('Thanks to this module you can start write yours module :)', $this->name );
    }



    public function install()
    {
        if (!parent::install()) {
            return false;
        }
        if (!$this->registerHook('displayProductAdditionalInfo'))
            return false;

        if (!$this->registerHook('displayHeader'))
            return false;

        return true;
    }

    public function uninstall()
    {

        return parent::uninstall();
    }

    public function displayForm()
    {

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Setting price history'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Klucz API'),
                    'name' => 'apikey',
                    'lang' => false,
                    'required' => true,

                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        );

        $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        $helper->tpl_vars = array(
            'fields_value' => array(
                'apikey' => Configuration::get('apikey'),


            ),
            'languages' => $this->context->controller->getLanguages(),
        );

        return $helper->generateForm($fields_form);
    }


    public function getContent()
    {
        $output = null;
        $this->context->controller->addJquery();
        if (Tools::isSubmit('submit' . $this->name)) {

            $apikey = Tools::getValue('apikey');



            if (!isset($chart_styles)) {
                $output .= $this->displayError($this->l('You have empty fields.'));
            } else {

                $resultUpdate = Configuration::updateValue('apikey', $apikey);


                $output .= $this->displayConfirmation($this->l('Successful save'));
            }
        }

        return $output . $this->displayForm();
    }

    public function hookDisplayHeader($params)
    {
        // Includes JS and CSS to header
        $res = $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/views/css/style.css');
        $this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/views/js/main.js');
        // Start AJAX method
        // Link to Ajax connect
        $link = new Link;
        $parameters_data= array("action" => "render");
        $ajax_get_data = $link->getModuleLink('getaddressdetails', 'ajax', $parameters_data);


        Media::addJsDef(array(
            "ajax_get_data" => $ajax_get_data
        ));
    }




    public function hookDisplayProductAdditionalInfo($params){


        $apikey = Configuration::get('apikey');

        $this->context->smarty->assign(array(
            'apikey' => strval($apikey),
        ));
        return $this->display(__FILE__, 'pricehistory.tpl');
    }

}
