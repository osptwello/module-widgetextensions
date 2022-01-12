<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 11/21/17
 * Time: 2:24 PM
 */

namespace Riff\WidgetExtensions\Block\Widget;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Widget\Block\BlockInterface;
use Riff\WidgetExtensions\Model\Widget;

/**
 * Class BaseWidget
 *
 * @package Riff\WidgetExtensions\Block\Widget
 */
class BaseWidget extends Template implements BlockInterface
{
    /**
     * BaseWidget constructor.
     *
     * @param Context               $context
     * @param array                 $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param $imageName
     *
     * @return string
     */
    public function getImageUrl($imageName)
    {
        $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return $mediaDirectory . $imageName;
    }

    /**
     * @param string $key
     * @param null $index
     * @return bool|mixed|string
     */
    public function getData($key = '', $index = null)
    {
        $data = parent::getData($key, $index);

        if (in_array($key, Widget::WYSIWYG_FIELD)) {
            if (base64_encode(base64_decode(str_replace(" ", "+", $data), true)) === str_replace(" ", "+", $data) && mb_detect_encoding(base64_decode(str_replace(" ", "+", $data), true), 'UTF-8', true)) {
                $data = base64_decode(str_replace(" ", "+", $data));
            }
        }

        return $data;
    }
}
