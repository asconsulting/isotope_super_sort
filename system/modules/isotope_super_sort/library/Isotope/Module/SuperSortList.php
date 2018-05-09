<?php

/**
 * Isotope Super Sort
 *
 * Copyright (C) 2018 Andrew Stevens Consulting
 *
 * @package    asconsulting/isotope_super_sort
 * @link       https://andrewstevens.consulting
 */

 
namespace Isotope\Module;

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
		
		$arrProducts = parent::findProducts($arrCacheIds);

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
