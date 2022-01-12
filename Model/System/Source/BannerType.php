<?php

namespace Riff\WidgetExtensions\Model\System\Source;

use Magento\Framework\Option\ArrayInterface;

class BannerType implements ArrayInterface
{
    public function toOptionArray()
    {
        $options = [
            [
                'value' => 'main',
                'label' => __('Main banner')
            ],
            [
                'value' => 'left',
                'label' => __('Left content')
            ],
            [
                'value' => 'right',
                'label' => __('Right content')
            ]
        ];

        return $options;
    }
}
