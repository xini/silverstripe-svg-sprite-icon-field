<?php

namespace Innoweb\SvgSpriteIconField;

use DOMDocument;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;

class IconField extends OptionsetField
{
    public function __construct($name, $title = null)
    {
        parent::__construct($name, $title, []);
        $this->setSourceIcons();
    }

    public function setSourceIcons()
    {
        $icons = [];
        $path = ModuleResourceLoader::resourcePath(Icon::config()->get('icon_sprite'));
        if ($path && file_exists(BASE_PATH . DIRECTORY_SEPARATOR . $path)) {
            $dom = new DOMDocument;
            $dom->loadXML(file_get_contents(BASE_PATH . DIRECTORY_SEPARATOR . $path));
            $symbols = $dom->getElementsByTagName('symbol');
            foreach ($symbols as $symbol) {
                $key = $symbol->getAttribute('id');
                $icons[$key] = ModuleResourceLoader::resourceURL(Icon::config()->get('icon_sprite')) . '#' . $key;
            }

            ksort($icons);
        }
        $this->source = $icons;
        return $this;
    }

    public function Field($properties = [])
    {
        Requirements::css('innoweb/silverstripe-svg-sprite-icon-field: client/css/IconField.css');
        $source = $this->getSource();
        $options = [];

        // Add a clear option
        $options[] = ArrayData::create([
            'ID' => 'none',
            'Name' => $this->name,
            'Value' => '',
            'Title' => '',
            'isChecked' => (!$this->value || $this->value == '')
        ]);

        if ($source) {
            foreach ($source as $key => $url) {
                $itemID = $this->ID() . '_' . preg_replace('/[^a-zA-Z0-9]/', '', $key);
                $options[] = ArrayData::create([
                    'ID' => $itemID,
                    'Name' => $this->name,
                    'Value' => $key,
                    'Label' => $key,
                    'URL' => $url,
                    'isChecked' => $key == $this->value
                ]);
            }
        }

        $properties = array_merge($properties, [
            'Options' => ArrayList::create($options)
        ]);

        return FormField::Field($properties);
    }

    /**
     * Handle extra classes
     **/
    public function extraClass()
    {
        $classes = ['field', 'IconField', parent::extraClass()];

        if (($key = array_search('icon', $classes)) !== false) {
            unset($classes[$key]);
        }

        return implode(' ', $classes);
    }
}
