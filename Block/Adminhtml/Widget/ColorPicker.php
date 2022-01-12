<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 3/19/17
 * Time: 4:28 PM
 */

namespace Riff\WidgetExtensions\Block\Adminhtml\Widget;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context as TemplateContext;
use Magento\Framework\Data\Form\Element\AbstractElement as Element;
use Magento\Framework\Data\Form\Element\Factory as FormElementFactory;

class ColorPicker extends Template
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $elementFactory;

    /**
     * @param TemplateContext    $context
     * @param FormElementFactory $elementFactory
     * @param array              $data
     */
    public function __construct(
        TemplateContext $context,
        FormElementFactory $elementFactory,
        $data = []
    ) {
        $this->elementFactory = $elementFactory;
        parent::__construct($context, $data);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param Element $element
     *
     * @return Element
     */
    public function prepareElementHtml(Element $element)
    {
        $value = $element->getData('value');

        /** @var \Magento\Framework\Data\Form\Element\Text $input */
        $input = $this->elementFactory->create("text", ['data' => $element->getData()]);
        $input->setId($element->getId());
        $input->setForm($element->getForm());
        $input->setClass("widget-option input-text admin__control-text");
        if ($element->getRequired()) {
            $input->addClass('required-entry');
        }

        $script = '<script type="text/javascript">
            require(["jquery", "jquery/colorpicker/js/colorpicker"], function ($) {
                $(document).ready(function () {
                    var $el = $("#' . $element->getId() . '");
                    $el.css("backgroundColor", "' . $value . '");

                    var colorPicker = $el.ColorPicker({
                        color: "' . $value . '",
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        },
                        onBeforeShow: function(){
                            jQuery("#" + colorPicker.data("colorpickerId")).css("z-index", 10000);     
                        }
                    });
                   
                });
            });
            </script>';

        $element->setData('after_element_html', $input->getElementHtml() . $script);

        return $element;
    }
}