<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 12/12/17
 * Time: 2:48 PM
 */

namespace Riff\WidgetExtensions\Block\Adminhtml\Category;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\View\Asset\GroupedCollection;
use Magento\Framework\View\Asset\Repository;
use Magento\Review\Model\ResourceModel\Review\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class WidgetTable extends Template
{
    /**
     * @var UrlInterface
     */
    protected $backendUrl;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var CollectionFactory
     */
    protected $reviewCollectionFactory;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * WidgetTable constructor.
     *
     * @param Context                             $context
     * @param UrlInterface                        $backendUrl
     * @param GroupedCollection                   $assetCollection
     * @param Registry                            $registry
     * @param CollectionFactory                   $collectionFactory
     * @param Data $jsonHelper
     * @param array                               $data
     */
    public function __construct(
        Context $context,
        UrlInterface $backendUrl,
        GroupedCollection $assetCollection,
        Registry $registry,
        CollectionFactory $collectionFactory,
        Data $jsonHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->backendUrl = $backendUrl;
        $this->registry = $registry;
        $this->reviewCollectionFactory = $collectionFactory;
        $this->jsonHelper = $jsonHelper;
    }

    /**
     * @return string Add urls to window
     */
    public function getAfterElementJs()
    {

        $script = '<script type="text/javascript">
                        require([\'jquery\'], function(){ 
                            window.widgetWindowUrl = "' . $this->getWidgetWindowUrl() . '";
                            window.widgetRendererUrl = "' . $this->getWidgetRenderUrl() . '";'
                            . ($this->getReviewIds() == "" ? "" : 'window.reviews = ' . $this->getReviewIds()  . ';')
                        . '});
                   </script>';

        return $script;
    }

    /**
     * @return bool|string
     */
    public function getReviewIds()
    {
        if (!$this->registry->registry('current_product')) {
            return false;
        }

        $reviews = $this->reviewCollectionFactory
            ->create()
            ->addFieldToSelect('review_id')
            ->addEntityFilter('product', $this->registry->registry('current_product')->getId())
            ->getData();

        return $this->jsonHelper->jsonEncode($reviews);
    }

    /**
     * @return string The magento Widget modal URL
     */
    public function getWidgetWindowUrl()
    {
        $params = [];

        return $this->backendUrl->getUrl('adminhtml/widget/index', $params);
    }

    /**
     * @return string The frontend widget render URL
     */
    public function getWidgetRenderUrl()
    {
        $storeId = $this->_storeManager->getDefaultStoreView()->getId();

        return $this->_storeManager->getStore($storeId)->getBaseUrl() . 'riff_widgetextensions/catalog/widgetrenderer';
    }

    /**
     * Produce and return block's html output
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->getAfterElementJs();
    }
}
