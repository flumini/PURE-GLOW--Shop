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
 * @package    Shopware_Controllers_Frontend_SwagVariantsInListing
 * @copyright  Copyright (c) 2012, shopware AG (http://www.shopware.de)
 */
class Shopware_Controllers_Frontend_SwagVariantsInListing extends Enlight_Controller_Action
{
    /**
     *
     * @var \Shopware\Models\Media\Repository
     */
    protected $mediaRepository = null;
 
    /**
     * Helper function to get access to the media repository.
     * @return \Shopware\Models\Media\Repository
     */
    private function getMediaRepository()
    {
        if ($this->mediaRepository === null) {
            $this->mediaRepository = Shopware()->Models()->getRepository('Shopware\Models\Media\Media');
        }
        return $this->mediaRepository;
    }
 
    /**
     * Controller action function which can be called over an ajax request.
     * This function is used to get the cover for a option and group configuration.
     */
    public function getCoverAction()
    {
        $articleId = $this->Request()->getParam('articleId', null);
        echo "in here ".$articleId
        $groupParams = $this->Request()->getParam('groups', array());
        $groups = array();
 
        foreach($groupParams as $key => $value) {
            $key = str_replace('group-', '', $key);
            $groups[$key] = $value;
        }
 
        $cover = $this->getCover($articleId, $groups);
         
        Shopware()->Plugins()->Controller()->ViewRenderer()->setNoRender();
 
        echo json_encode(array('cover' => $cover));
    }
 
    /**
     * Helper function to get the cover for the passed article id and configuration groups
     *
     * @param $articleId
     * @param $groups
     *
     * @return array
     * @throws Exception
     */
    private function getCover($articleId, $groups) {
        //first we have to select the first variant for the passed groups/options
        $variant = $this->getVariantForOptions($articleId, $groups);
 
        //if no variant was selected, we throw a new exception
        if (!$variant instanceof \Shopware\Models\Article\Detail) {
            throw new Exception('Variant not found!', 500);
        }

        //after we have the variant, we can use the shopware function getArticleCover to get the cover of the variant
        return Shopware()->Modules()->Articles()->getArticleCover(
            $articleId,
            $variant->getNumber(),
            $this->getArticleAlbum()
        );
    }
    
    /**
     * Get a reference to the sArticle module
     *
     * @return sArticles
     */
    private function getArticleModule() {
        return Shopware()->Modules()->Articles();
    }
 
    /**
     * Helper function to get a single variant for the passed article id and options.
     *
     * @param $articleId
     * @param $options
     *
     * @return mixed
     */
    private function getVariantForOptions($articleId, $options) {
        /**@var $repository \Shopware\Models\Article\Repository*/
        $repository = Shopware()->Models()->getRepository('Shopware\Models\Article\Article');
 
        $builder = $repository->getDetailsForOptionIdsQueryBuilder($articleId, $options);
 
        $builder->setFirstResult(0)
                ->setMaxResults(1);
 
        /**@var $variant \Shopware\Models\Article\Detail*/
        return $builder->getQuery()->getOneOrNullResult(
            \Doctrine\ORM\AbstractQuery::HYDRATE_OBJECT
        );
    }
 
    /**
     * Internal helper function to get the article media album
     * @return \Shopware\Models\Media\Album
     */
    private function getArticleAlbum() {
        return $this->getMediaRepository()
                ->getAlbumWithSettingsQuery(-1)
                ->getOneOrNullResult();
    }
}
