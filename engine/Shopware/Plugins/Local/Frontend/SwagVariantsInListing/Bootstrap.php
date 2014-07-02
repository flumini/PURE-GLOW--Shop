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
 */
 
/**
 * Plugin bootstrapping class.
 *
 * @category Shopware
 * @package Shopware\Plugin\SwagAvailabilityCheck
 * @copyright Copyright (c) 2012, shopware AG (http://www.shopware.de)
 */
class Shopware_Plugins_Frontend_SwagVariantsInListing_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Returns the plugin label which displayed in the plugin information and
     * in the plugin manager.
     * @return string
     */
    public function getLabel() {
        return 'Varianten Übersicht im Listing';
    }
 
    /**
     * Returns the plugin information
     * @return array
     */
    public function getInfo() {
        return array(
            'label' => $this->getLabel(),
            'version' => $this->getVersion(),
            'link' => 'http://www.shopware.de/'
        );
    }
 
    /**
     * Returns the plugin version.
     *
     * @return string
     */
    public function getVersion() {
        return '1.0.0';
    }
 
    /**
     * Plugin install function which registers all required Shopware events.
     * @return bool
     */
    public function install() {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Frontend_Listing',
            'onFrontendPostDispatch'
        );
        
        
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail',
            'onPostDispatchDetail'
        );
      
 
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_SwagVariantsInListing',
            'onGetFrontendController'
        );
        

        return true;
    }
 
    /**
     * Post dispatch event of the frontend listing controller.
     *
     * @param Enlight_Event_EventArgs $arguments
     */
    public function onFrontendPostDispatch(Enlight_Event_EventArgs $arguments)
    {
    echo "in this func";
        /**@var $controller Shopware_Controllers_Frontend_Index */
        $controller = $arguments->getSubject();
        $view = $controller->View();
        $request = $controller->Request();
        if ($request->getControllerName() !== 'listing'
                || $request->getModuleName() !== 'frontend'
                || !$view->hasTemplate()) {
                //echo "false";
            return;
        }
        $articles = $view->getAssign('sArticles');
        foreach($articles as &$article) {
        if (!$article['sConfigurator']) {
                continue;
            }
            $article['swagVariantsInListing'] = $this->getArticleConfiguration($article['articleID']);
        }
 
        //Add our plugin template directory
        $view->addTemplateDir($this->Path() . 'Views/');
        $view->extendsTemplate('frontend/listing/extension.tpl');
        $view->extendsTemplate('frontend/index/extension.tpl');
        $view->assign('sArticles', $articles);
    }


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
        foreach($articles as &$article) {
        if (!$article['sConfigurator']) {
                continue;
            }
            $article['swagVariantsInListing'] = $this->getArticleConfiguration($article['articleID']);
        }
 
        //Add our plugin template directory
        $view->addTemplateDir($this->Path() . 'Views/');
        $view->extendsTemplate('widgets/recommendation/extends.tpl');
        $view->assign('sArticles', $articles);
    }
 
    /**
     * Event listener function which returns the controller path of the plugin frontend controller.
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
        return $this->Path() . 'Controllers/Frontend/SwagVariantsInListing.php';
    }
 
    /**
     * Helper function to get all possible configurator options for the passed
     * article id.
     *
     * @param $articleId
     *
     * @return array
     */
    private function getArticleConfiguration($articleId) {
       //creates an empty query builder object
   		$builder = Shopware()->Models()->createQueryBuilder();
 
   		//add the select and from path for the query
   		$builder->select(array('articles', 'images'))
         ->from('Shopware\Models\Article\Article', 'articles')
         ->leftJoin('articles.images', 'images')
         ->where('articles.id = :articleId');
         //->andWhere('images.main = 1');

        $builder->setParameters(array('articleId' => $articleId));
 
   		//get generated query object from the builder object
   		//$query = $builder->getQuery();
 
   		//set hydration mode to get the result as array data
   		//$query->setHydrationMode(
      	//	\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY
   		//);
 
   		//get paginator extension to get the query result
   		//$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
 
   		//get an array copy of the paginator result.
   		//$articles = $paginator->getIterator()->getArrayCopy();
 
   		//return $articles;
        //return the query result as array
        $mydata = $builder->getQuery()->getArrayResult();

        $return = "http://".Shopware()->Config()->BasePath. '/media/image/thumbnail/'.$mydata[0]['images'][0]['path']."_231x300.".$mydata[0]['images'][0]['extension'];
        
        //$builder2 = Shopware()->Models()->createQueryBuilder();
 
   		//add the select and from path for the query
   		/*$builder2->select(array('articles', 'images'))
         ->from('Shopware\Models\Article\Article', 'articles')
         ->leftJoin('articles.images', 'images')
         ->where('articles.id = :articleId');

        $builder2->setParameters(array('articleId' => $articleId));
        
        $mydata2 = $builder2->getQuery()->getArrayResult();
        $thisnumber = count($mydata2[0]['images']) - 1;
*/
        $return2 = "http://".Shopware()->Config()->BasePath. '/media/image/thumbnail/'.$mydata[0]['images'][1]['path']."_231x300.".$mydata[0]['images'][1]['extension'];
        
        return  array($return, $return2);
        //return $builder->getQuery()->getArrayResult();
        //return $articles;
    }
}