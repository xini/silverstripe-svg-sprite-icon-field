<?php

namespace Innoweb\SvgSpriteIconField;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Manifest\ModuleResourceLoader;
use SilverStripe\ORM\Connect\MySQLDatabase;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\FieldType\DBField;

class Icon extends DBField
{
    private static $css_class = 'SpriteIcon';

    private static $icon_width = '16';

    private static $icon_height = '16';

    private static $icon_sprite = 'innoweb/silverstripe-svg-sprite-icon-field: client/icons/fontawesome-regular-free.svg';

    private static $casting = array(
        'URL' => 'HTMLFragment',
        'IMG' => 'HTMLFragment',
        'SVG' => 'HTMLFragment'
    );

    public function requireField()
    {
        $charset = Config::inst()->get(MySQLDatabase::class, 'charset');
        $collation = Config::inst()->get(MySQLDatabase::class, 'collation');

        $parts = [
            'datatype' => 'varchar',
            'precision' => 255,
            'character set' => $charset,
            'collate' => $collation,
            'arrayValue' => $this->arrayValue
        ];

        $values = [
            'type' => 'varchar',
            'parts' => $parts
        ];

        DB::require_field($this->tableName, $this->name, $values);
    }

    public function forTemplate()
    {
        return $this->customise([
            'URL' => ModuleResourceLoader::resourceURL(self::config()->get('icon_sprite')),
            'Key' => $this->getValue(),
            'Width' => self::config()->get('icon_width'),
            'Height' => self::config()->get('icon_height'),
            'Class' => self::config()->get('css_class'),
        ])->renderWith(
            $this->getViewerTemplates()
        );
    }

    /**
     * (non-PHPdoc)
     * @see DBField::scaffoldFormField()
     */
    public function scaffoldFormField($title = null, $params = null)
    {
        return IconField::create($this->name, $title);
    }
}
