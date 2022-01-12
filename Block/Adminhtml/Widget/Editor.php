<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 3/19/17
 * Time: 4:28 PM
 */

namespace Riff\WidgetExtensions\Block\Adminhtml\Widget;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Data\Form\Element\Factory;

class Editor extends Template
{
    /**
     * @var Config
     */
    protected $_wysiwygConfig;

    /**
     * @var Factory
     */
    protected $_factoryElement;

    /**
     * @param Factory $factoryElement
     * @param CollectionFactory $factoryCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Factory $factoryElement,
        Config $wysiwygConfig,
        $data = []
    ) {
        $this->_factoryElement = $factoryElement;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $data);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param AbstractElement $element Form Element
     *
     * @return AbstractElement
     */
    public function prepareElementHtml(AbstractElement $element)
    {
        $editor = $this->_factoryElement->create('editor',
            ['data' => $element->getData()])->setLabel('')->setForm($element->getForm())->setWysiwyg(true)->setConfig($this->_wysiwygConfig->getConfig([
                'add_variables' => false,
                'add_widgets' => false
            ]));

        if ($element->getRequired()) {
            $editor->addClass('required-entry');
        }

        $element->setData('after_element_html', $this->_getAfterElementHtml() . $editor->getElementHtml());

        return $element;
    }

    /**
     * @return string
     */
    protected function _getAfterElementHtml()
    {
        $html = '<style>.admin__field-control.control .control-value { display: none !important; }</style>';

        return $html;
    }
}
