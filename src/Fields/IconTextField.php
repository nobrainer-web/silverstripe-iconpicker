<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 27/11/2018
 * Time: 15.03
 */

namespace NobrainerWeb\IconPicker\Fields;

use NobrainerWeb\IconPicker\Helper\Helper;
use NobrainerWeb\IconPicker\Traits\FieldTraits;
use SilverStripe\Core\Path;
use SilverStripe\Forms\TextField;
use SilverStripe\View\Requirements;

class IconTextField extends TextField
{
    use FieldTraits;

    /**
     * @config
     * @var array $default_classes The default classes to apply to the FormField
     */
    private static $default_classes = [
        'text'
    ];

    /**
     * Validate this field
     *
     * @param \SilverStripe\Forms\Validation\Validator $validator
     * @return bool
     */
    /***
     * @return string
     */
    private function getSVGPath()
    {
        return Path::join(Helper::getCMSSourceFolder(), $this->getValue() . '.svg');
    }

    public function Field($properties = [])
    {
        Requirements::javascript('nobrainerweb/silverstripe-iconpicker:client/nw-iconpicker.js');
        Requirements::css('nobrainerweb/silverstripe-iconpicker:client/nw-iconpicker.css');

        $this->addExtraClass('nw-icon-textfield js-nw-icon-textfield');

        $properties['Icon'] = $this->getSVGPath();

        return parent::Field($properties);
    }
}