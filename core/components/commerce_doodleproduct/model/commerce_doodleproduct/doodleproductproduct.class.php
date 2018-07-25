<?php
use modmore\Commerce\Admin\Widgets\Form\SelectField;
use modmore\Commerce\Admin\Widgets\Form\TextField;
use modmore\Commerce\Admin\Widgets\Form\Tab;

/**
 * DoodleProduct for Commerce.
 *
 * Copyright 2018 by Tony Klapatch for modmore <support@modmore.com>
 *
 * This file is meant to be used with Commerce by modmore. A valid Commerce license is required.
 *
 * @package commerce_doodleproduct
 * @license See core/components/commerce_doodleproduct/docs/license.txt
 */
class DoodleproductProduct extends comProduct
{
    public function getModelFields()
    {
        /*
            Since we want to add on to the existing fields, we want to get the
            existing fields by calling the base product which this inherits from.
        
            This gets all the existing fields as an array you can manipulate, for
            example, you could change the order, or insert your field at a position
            with a foreach and an array_splice.

            For an example of inserting a field at position, check out this file
            in the RandomlyPricedProduct module on the modmore github.
        */
        $fields = parent::getModelFields();

        $newFields = [];

        // Add a new tab to the form after all the other tabs
        $newFields[] = new Tab($this->commerce, [
            'label' => $this->adapter->lexicon('commerce_doodleproduct.shirt_tab')
        ]);

        // Add a select field inside the tab
        $newFields[] = new SelectField($this->commerce, [
            'label' => $this->adapter->lexicon('commerce_doodleproduct.shirt_size'),
            'name' => 'properties[shirt_size]',
            'value' => $this->getProperty('shirt_size'),
            'options' => [
                ['value' => 'S', 'label' => $this->adapter->lexicon('commerce_doodleproduct.shirt_s')],
                ['value' => 'M', 'label' => $this->adapter->lexicon('commerce_doodleproduct.shirt_m')],
                ['value' => 'L', 'label' => $this->adapter->lexicon('commerce_doodleproduct.shirt_l')],
                ['value' => 'XL', 'label' => $this->adapter->lexicon('commerce_doodleproduct.shirt_xl')]
            ]
        ]);

        // Add a text field after the select field
        $newFields[] = new TextField($this->commerce, [
            'label' => $this->adapter->lexicon('commerce_doodleproduct.shirt_color'),
            'name' => 'properties[shirt_color]',
            'value' => $this->getProperty('shirt_color')
        ]);

        // Merging the existing fields with the new fields we just added.
        return array_merge($fields, $newFields);
    }
}
