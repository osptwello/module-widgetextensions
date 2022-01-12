<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 3/19/17
 * Time: 5:06 PM
 */

namespace Riff\WidgetExtensions\Model;

use Magento\Widget\Model\Widget as BaseWidget;

/**
 * Class Widget
 *
 * @package Riff\WidgetExtensions\Model
 */
class Widget
{
    //Add image fields here
    const IMAGE_FIELDS = [
        'widget_image_chooser',
        'image',
        'image_1',
        'image_2',
        'image_3',
        'image_4',
        'image_5',
        'image_desktop',
        'image_mobile',
        'image_1_mobile',
        'image_2_mobile',
        'image_3_mobile',
        'image_4_mobile',
        'image_5_mobile'
    ];

    //Add content
    const WYSIWYG_FIELD = [
        'content'
    ];

    /**
     * @param BaseWidget $subject
     * @param            $type
     * @param array      $params
     * @param bool       $asIs
     *
     * @return array
     */
    public function beforeGetWidgetDeclaration(BaseWidget $subject, $type, $params = [], $asIs = true)
    {
        $urls = [];

        foreach (Widget::IMAGE_FIELDS as $sKey) {
            if (key_exists($sKey, $params)) {
                $urls[$sKey] = $params[$sKey];
            }
        }

        foreach ($urls as $item => $url) {
            if (strpos($url, '/directive/___directive/') !== false) {
                $parts = explode('/', $url);
                $key = array_search("___directive", $parts);
                if ($key !== false) {
                    $url = $parts[$key + 1];
                    $url = base64_decode(strtr($url, '-_,', '+/='));

                    $parts = explode('"', $url);
                    $key = array_search("{{media url=", $parts);
                    $url = $parts[$key + 1];

                    $params[$item] = $url;
                }
            }
        }

        foreach ($params as $key => $param) {
            if (in_array($key, self::WYSIWYG_FIELD)) {
                if (base64_encode(base64_decode(str_replace(" ", "+", $param), true)) === str_replace(" ", "+", $param) && mb_detect_encoding(base64_decode(str_replace(" ", "+", $param), true), 'UTF-8', true)) {
                    $params[$key] = $param;
                } else {
                    $params[$key] = base64_encode($param);
                }
            }
        }

        return array($type, $params, $asIs);
    }
}
