<?php
/**
 * Created by PhpStorm.
 * User: sanderhagenaars
 * Date: 27/11/2018
 * Time: 15.03
 */

namespace NobrainerWeb\IconPicker\Fields;

use NobrainerWeb\IconPicker\Helper\Helper;
use SilverStripe\Core\Path;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\Validator;
use SilverStripe\View\Requirements;

class IconTextField extends TextField
{
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
     * @param Validator $validator
     * @return bool
     */
    public function validate($validator)
    {
        // TODO check if file exists

        return parent::validate($validator);
    }

    /***
     * @return string
     */
    private function getSVGPath()
    {
        return Path::join(Helper::getCMSSourceFolder(), $this->Value() . '.svg');
    }

    public function Field($properties = [])
    {
        Requirements::javascript('nobrainerweb/silverstripe-iconpicker:client/nw-iconpicker.js');
        Requirements::css('nobrainerweb/silverstripe-iconpicker:client/nw-iconpicker.css');

        $this->setDescription(Helper::getFieldDescription($this));
        $this->addExtraClass('nw-icon-textfield js-nw-icon-textfield');

        $properties['Icon'] = $this->getSVGPath();

        return parent::Field($properties);
    }
}