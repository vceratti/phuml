<?php

use PhUml\Graphviz\HtmlLabelStyle;

class plGraphvizProcessorOptions extends plProcessorOptions
{
    public function __construct()
    {
        $this->properties = [
            'style' => [
                'data' => new HtmlLabelStyle(),
                'type' => self::STRING,
                'description' => 'Style to use for the dot creation'
            ],
            'createAssociations' => [
                'data' => true,
                'type' => self::BOOL,
                'description' => 'Create connections between classes that include each other. (This information can only be extracted if it is present in docblock comments)'
            ],
        ];
    }

    public function __set($key, $val)
    {
        switch ($key) {
            case 'style':
                $this->properties[$key]['data'] = HtmlLabelStyle::factory((string)$val);
                break;
            case 'createAssociations':
                $this->properties[$key]['data'] = ($val === '0' || $val === 'false') ? false : true;
                break;
            default:
                throw new plProcessorOptionException($key, plProcessorOptionException::WRITE);
        }

    }
}
