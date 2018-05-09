<?php

/**
 * Isotope Super Sort
 *
 * Copyright (C) 2018 Andrew Stevens Consulting
 *
 * @package    asconsulting/isotope_super_sort
 * @link       https://andrewstevens.consulting
 */
 
 
/**
 * Isotope eCommerce for Contao Open Source CMS
 *
 * Copyright (C) 2009-2014 terminal42 gmbh & Isotope eCommerce Workgroup
 *
 * @package    Isotope
 * @link       http://isotopeecommerce.org
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

 
namespace Isotope\Module;

use Haste\Generator\RowClass;
use Haste\Http\Response\HtmlResponse;
use Haste\Input\Input;
use Isotope\Isotope;
use Isotope\Model\Attribute;
use Isotope\Model\Product;
use Isotope\Model\ProductCache;
use Isotope\Model\ProductType;
use Isotope\RequestCache\FilterQueryBuilder;
use Isotope\RequestCache\Sort;
use Isotope\Module\ProductList;
/**
 * @property string $iso_list_layout
 * @property int    $iso_cols
 * @property bool   $iso_use_quantity
 * @property int    $iso_gallery
 * @property array  $iso_filterModules
 * @property array  $iso_productcache
 * @property string $iso_listingSortField
 * @property string $iso_listingSortDirection
 * @property bool   $iso_jump_first
 */
class SuperSortList extends ProductList
{
 
	/**
     * Find all products we need to list.
     *
     * @param array|null $arrCacheIds
     *
     * @return array
     */
    protected function findProducts($arrCacheIds = null)
    {
		global $objPage;
		
        $arrColumns    = array();
        $arrCategories = $this->findCategories();
        $queryBuilder  = new FilterQueryBuilder(
            Isotope::getRequestCache()->getFiltersForModules($this->iso_filterModules)
        );

        $arrColumns[]  = "c.page_id IN (" . implode(',', $arrCategories) . ")";

        if (!empty($arrCacheIds) && is_array($arrCacheIds)) {
            $arrColumns[] = Product::getTable() . ".id IN (" . implode(',', $arrCacheIds) . ")";
        }

        // Apply new/old product filter
        if ('show_new' === $this->iso_newFilter) {
            $arrColumns[] = Product::getTable() . ".dateAdded>=" . Isotope::getConfig()->getNewProductLimit();
        } elseif ('show_old' === $this->iso_newFilter) {
            $arrColumns[] = Product::getTable() . ".dateAdded<" . Isotope::getConfig()->getNewProductLimit();
        }

        if ($this->iso_list_where != '') {
            $arrColumns[] = $this->iso_list_where;
        }

        if ($queryBuilder->hasSqlCondition()) {
            $arrColumns[] = $queryBuilder->getSqlWhere();
        }

        $arrSorting = Isotope::getRequestCache()->getSortingsForModules($this->iso_filterModules);

        if (empty($arrSorting) && $this->iso_listingSortField != '') {
            $direction = ('DESC' === $this->iso_listingSortDirection ? Sort::descending() : Sort::ascending());
            $arrSorting[$this->iso_listingSortField] = $direction;
        }

        $objProducts = Product::findAvailableBy(
            $arrColumns,
            $queryBuilder->getSqlValues(),
            array(
                 'order'   => 'c.sorting',
                 'filters' => $queryBuilder->getFilters(),
                 'sorting' => $arrSorting,
            )
        );

		$arrProducts = (null === $objProducts) ? array() : $objProducts->getModels();

		$arrOrderedProducts = array();
		$arrUnorderedProducts = array();
		$arrOrder = deserialize($objPage->iso_product_order);
		if (is_array($arrOrder) && !empty($arrOrder)) {
			foreach ($arrOrder as $product) {
				foreach($arrProducts as $objProduct) {
					if ($objProduct->id == $product) {
						$arrOrderedProducts[] = $objProduct;
					}
				}			
			}

			foreach($arrProducts as $objProduct) {
				if (!in_array($objProduct->id, $arrOrder)) {
					$arrUnorderedProducts[] = $objProduct;
				}
			}
			
			$arrProducts = array_merge($arrOrderedProducts, $arrUnorderedProducts);
		}
		
        return $arrProducts;
    }
	
}
