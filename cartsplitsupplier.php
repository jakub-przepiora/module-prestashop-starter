<?php

/**
 * LICENCE
 *
 * ALL RIGHTS RESERVED.
 * YOU ARE NOT ALLOWED TO COPY/EDIT/SHARE/WHATEVER.
 *
 * IN CASE OF ANY PROBLEM CONTACT AUTHOR.
 *
 *  @author    Jakub Przepióra (kontakt@nice-code.eu)
 *  @copyright nice-code.pl
 *  @license   ALL RIGHTS RESERVED
 */


require_once dirname(__FILE__) . 'classes/DatabaseInteraction.php';
require_once dirname(__FILE__) . 'classes/HookModuleMenager.php';

if (!defined('_PS_VERSION_'))
    exit();

class Cartsplitsupplier extends Module
{
    public $cartsplitsupplier;


    /** Only enable one execution per Module instance */
    public static $executed = false;

    /**
     *
     * @var array list of db tables
     */
    private $databases = ["cart_split_supplier"=> "cart_split_supplier"];

    public function __construct()
    {
        $this->name = 'cart_split_supplier';
        $this->version = '1.0.0';
        $this->author = 'Jakub Przepióra';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->file_start_dir = __FILE__;
        parent::__construct();

        $this->displayName = 'Cart split by supplier';
        $this->description = 'Thanks to this module you split your cart to smaller  with supplier';
    }









    public function install()
    {
        // Create database
        if (!DatabaseInteraction::install()) return false;

        // install module (enable module)
        if (!parent::install()) return false;

        // hook register
        if (!HookModuleMenager::registerModuleHooks()) return false;


        return true;
    }

    public function uninstall()
    {
        DatabaseInteraction::uninstall();

        return parent::uninstall();
    }



    /*
     * Hooks section
     * */

    public function hookDisplayHeader($params) {
        $res = $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/views/css/style.css');
        $this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/views/js/main.js');

    }

}
