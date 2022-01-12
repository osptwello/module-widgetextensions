<?php
namespace Riff\WidgetExtensions\Controller\Catalog;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Result\Page;

class WidgetRenderer extends Action
{
    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * WidgetRenderer constructor.
     *
     * @param Context        $context
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        Context $context,
        FilterProvider $filterProvider
    ) {
        parent::__construct($context);

        $this->filterProvider = $filterProvider;
    }

    /**
     * @return bool
     */
    public function _isAllowed()
    {
        return true;
    }

    /**
     * Dispatch request
     *
     * @return ResponseInterface|ResultInterface|Page
     * @throws \Exception
     */
    public function execute()
    {
        $widgetData = base64_decode($this->getRequest()->getParam('widget'));

        $html = $this->filterProvider->getBlockFilter()->filter($widgetData);

        /**
         * @var $page Page
         */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        /**
         * @var $block Template
         */
        $block = $page->getLayout()->getBlock('widget_renderer');
        $block->setData('widget', $html);

        return $page;
    }
}