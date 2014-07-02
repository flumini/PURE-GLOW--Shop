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
 * @package    Shopware_Controllers_Frontend_SwagVariantsTab
 * @copyright  Copyright (c) 2013, shopware AG (http://www.shopware.de)
 */
class Shopware_Controllers_Frontend_SwagVariantsTab extends Enlight_Controller_Action
{
    protected $mediaRepository = null;
 
    /**
     * Get a reference to the sArticle module
     *
     * @return sArticles
     */
    private function getArticleModule() {
        return Shopware()->Modules()->Articles();
    }
 
    /**
     * Get the media repository
     *
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
     * Returns the media album used for articles
     *
     * @return \Shopware\Models\Media\Album|null
     */
    private function getArticleAlbum()
    {
        return $this->getMediaRepository()
                ->getAlbumWithSettingsQuery(-1)
                ->getOneOrNullResult();
    }
 
    /**
     * Get the configured number of variants from database
     */
    public function getVariantsAction() {
        Shopware()->Plugins()->Controller()->ViewRenderer()->setNoRender();
 
        $config = Shopware()->Plugins()->Frontend()->SwagVariantsTab()->Config();
        $perPage = $config->variantsPerPage;
 
        $articleId = $this->Request()->getParam('articleId');
        $page = $this->Request()->getParam('page', 1);
 
        list($variants, $totalResults) = $this->getArticleVariants($articleId, $page, $perPage);
 
        $numberOfPages = ceil($totalResults / $perPage);
 
        echo json_encode(array(
            'data' => $variants,
            'totalResults' => $totalResults,
            'numberOfPages' => $numberOfPages,
            'currentPage' => $page
        ));
    }
 
 
    /**
     * Helper method which returns a Query object in order to select the required variant data
     * @param $articleId
     * @param $offset
     * @param $limit
     * @return Doctrine\ORM\Query
     */
    protected function getVariantsQuery($articleId, $offset, $limit)
    {
        /** @var $article Shopware\Models\Article\Article */
        $article = Shopware()->Models()->find(
            '\Shopware\Models\Article\Article', $articleId
        );
 
        $builder = Shopware()->Models()->createQueryBuilder();
        $builder->select(array('detail', 'options'))
                ->from('Shopware\Models\Article\Detail', 'detail')
                ->leftJoin('detail.configuratorOptions', 'options')
                ->where('detail.articleId = :articleId')
                ->andWhere('detail.active = true ')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameters(array('articleId' => $articleId));
 
        if ($article->getLastStock()) {
            $builder->andWhere('detail.inStock > 0');
        }
 
        return $builder->getQuery();
    }
 
    /**
     * Helper method which gets variants for a given articleId and populates the output array with cover images
     *
     * @param $articleId
     * @param $page
     * @param $perPage
     * @return array
     */
    public function getArticleVariants($articleId, $page, $perPage)
    {
        // Get default article album
        $articleAlbum = $this->getArticleAlbum();
 
        $offset = --$page * $perPage;
 
        // Get variants for the article
        $query = $this->getVariantsQuery($articleId, $offset, $perPage);
 
        $query->setHydrationMode(
            \Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY
        );
 
        $paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
 
        $totalResult = $paginator->count();
        $variants = $paginator->getIterator()->getArrayCopy();
 
        // Get cover image for each variant
        foreach($variants as &$variant) {
            $variant['cover'] = $this->getArticleModule()->getArticleCover(
                $articleId,
                $variant['number'],
                $articleAlbum
            );
 
        }
 
        return array($variants, $totalResult);
    }
}
 