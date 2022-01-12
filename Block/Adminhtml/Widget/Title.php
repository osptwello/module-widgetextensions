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

class Title extends Template
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
        $css = array(
            "height:27px",
            "line-height:33px",
            "color:#666666",
            "margin-bottom: 0",
            "font-weight: bold",
            "font-size: 28px"
        );
        $script = '<h2 style="' . implode(";", $css) . ';">' . $this->getData('block_heading') . '</h2>';

        $element->setData('after_element_html', $script);

        return $element;
    }
}
