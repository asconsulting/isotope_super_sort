<?php

/**
 * Isotope Super Sort
 *
 * Copyright (C) 2018-2022 Andrew Stevens Consulting
 *
 * @package    asconsulting/isotope_super_sort
 * @link       https://andrewstevens.consulting
 */



foreach($GLOBALS['TL_DCA']['tl_page']['palettes'] as $key => $palette) {
	$GLOBALS['TL_DCA']['tl_page']['palettes'][$key] = str_replace(';{protected_legend:hide}', ';{product_sort_legend},iso_product_order;{protected_legend:hide}', $GLOBALS['TL_DCA']['tl_page']['palettes'][$key]);
}


/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_page']['fields']['iso_product_order'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_page']['iso_product_order'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkboxWizard',
	'foreignKey'              => 'tl_iso_products.name',
	'options_callback'        => array('SuperSort\Backend\SuperSortHelper', 'getProducts'),
	'eval'                    => array('multiple'=>true),
	'sql'                     => "blob NULL",
	'relation'                => array('type'=>'hasMany', 'load'=>'lazy')
);
