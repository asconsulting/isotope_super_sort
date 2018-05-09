<?php
 
/**
 * Isotope Super Sort
 *
 * Copyright (C) 2018 Andrew Stevens Consulting
 *
 * @package    asconsulting/isotope_super_sort
 * @link       https://andrewstevens.consulting
 */
 
 
namespace Isotope;
use Isotope\Model\Product;
use Isotope\RequestCache\FilterQueryBuilder;
 
class SuperSortHelper extends Backend
{


    /**
     * Find all products we need to list.
     *
     * @return array
     */
    function getProducts(\Contao\DataContainer $dc)
    {
		
		$objProducts = Product::findPublishedByCategories(array((int)$dc->id));
		
		$arrProducts = array();
		
		if (count($objProducts)) {
			while ($objProducts->next()) {
				$arrProducts[$objProducts->id] = $objProducts->name ." " .$objProducts->color_or_design_name ." (SKU: " .$objProducts->sku .")";
			}
		}

        return $arrProducts;

    }
	
}