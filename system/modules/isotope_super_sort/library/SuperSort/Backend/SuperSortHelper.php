<?php

/**
 * Isotope Super Sort
 *
 * Copyright (C) 2018-2022 Andrew Stevens Consulting
 *
 * @package    asconsulting/isotope_super_sort
 * @link       https://andrewstevens.consulting
 */



namespace SuperSort\Backend;


use Isotope\Model\Product;


class SuperSortHelper extends \Isotope\Backend
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
