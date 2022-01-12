<?php

namespace Riff\WidgetExtensions\Model\System\Source;

use Magento\Framework\Option\ArrayInterface;

class ExampleSource implements ArrayInterface
{
    public function toOptionArray()
    {
        $options = [
            [
                'value' => 'value_1',
                'label' => __('Value 1')
            ],
            [
                'value' => 'value_2',
                'label' => __('Value 2')
            ]
        ];

        return $options;
    }
}