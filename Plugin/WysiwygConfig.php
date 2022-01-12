<?php namespace Riff\WidgetExtensions\Plugin;

use Magento\Framework\DataObject;

class WysiwygConfig
{
    public function afterGetConfig($subject, DataObject $config)
    {
        $config->addData([
            'add_directives' => true,
        ]);

        return $config;
    }
}
