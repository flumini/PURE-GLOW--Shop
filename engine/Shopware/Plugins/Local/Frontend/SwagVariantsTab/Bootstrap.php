<?php
/**
 * Shopware 4.0
 * Copyright © 2012 shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 *
 * @category   Shopware
 * @package    Shopware_Plugins
 * @subpackage Plugin
 * @copyright  Copyright (c) 2012, shopware AG (http://www.shopware.de)
 * @version    $Id$
 * @author     shopware AG
 */
 
class Shopware_Plugins_Frontend_SwagVariantsTab_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Returns the current version of the plugin.
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }
 
    /**
     * Get (nice) name for plugin manager list
     * @return string
     */
    public function getLabel()
    {
        return 'Variantenreiter';
    }
 
    /**
     * Standard plugin install method to register all required components.
     * @throws \Exception
     * @return bool success
     */
    public function install()
    {
        $this->subscribeEvents();
        $this->createConfigForm();
 
        return true;
    }
 
    /**
     * @return bool
     */
    public function update()
    {
        return true;
    }
 
 
    /**
     * Creates the configuration form for the plugin
     *
     * @protected
     * @return void
     */
    protected function createConfigForm()
    {
        $form = $this->Form();
        $form->setElement('number', 'variantsPerPage', array(
            'label' => 'Anzahl der Varianten pro Seite',
            'minValue' => 1,
            'maxValue' => 100,
            'required' => true,
            'value' => 9,
            'scope' => \Shopware\Models\Config\Element::SCOPE_SHOP
        ));
    }
 
    /**
     * Registers all necessary events and hooks.
     */
    private function subscribeEvents()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail',
            'onPostDispatchDetail'
        );
 
        // Register frontend controller
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_SwagVariantsTab',
            'onGetFrontendController'
        );
    }
 
    /**
     * Event listener function which returns the controller path of the plugin widget controller.
     *
     * @param Enlight_Event_EventArgs $arguments
     *
     * @return string
     */
    public function onGetFrontendController(Enlight_Event_EventArgs $arguments)
    {
        $this->Application()->Template()->addTemplateDir(
            $this->Path() . 'Views/'
        );
        return $this->Path() . 'Controllers/Frontend/SwagVariantsTab.php';
    }
 
    /**
     * Event listener function which called over the Enlight_Controller_Action_PostDispatch_Frontend_Detail event.
     * The event fired when the customer enter an article detail page.
     *
     * @param Enlight_Event_EventArgs $arguments
     */
    public function onPostDispatchDetail(Enlight_Event_EventArgs $arguments)
    {
        echo "in here now";
        /**@var $controller Shopware_Controllers_Frontend_Index */
        $controller = $arguments->getSubject();
        $view = $controller->View();
        $request = $controller->Request();
        //if ($request->getControllerName() !== 'listing'
          //      || $request->getModuleName() !== 'frontend'
            //    || !$view->hasTemplate()) {
                //echo "false";
            //return;
        //}
        
        echo "ch bin heri";
        $articles = $view->getAssign('sArticles');
        /*foreach($articles as &$article) {
        if (!$article['sConfigurator']) {
                continue;
            }
            $article['swagVariantsInListing'] = $this->getArticleConfiguration($article['articleID']);
        }*/
        echo "now here";
 
        //Add our plugin template directory
        $view->addTemplateDir($this->Path() . 'Views/');
        $view->extendsTemplate('widgets/recommendation/extends.tpl');
        $view->assign('sArticles', $articles);
    }
 
}
 