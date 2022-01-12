<?php

namespace Riff\WidgetExtensions\Block\Category;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class WidgetRenderer extends Template
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * CategoryWidget constructor.
     *
     * @param Context                     $context
     * @param Registry                    $registry
     * @param Data                        $jsonHelper
     * @param FilterProvider              $filterProvider
     * @param CategoryRepositoryInterface $categoryRepository
     * @param array                       $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $jsonHelper,
        FilterProvider $filterProvider,
        CategoryRepositoryInterface $categoryRepository,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->registry = $registry;
        $this->jsonHelper = $jsonHelper;
        $this->filterProvider = $filterProvider;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Returns the correct widget via the itemID.
     *
     * @param int $itemID The current category item iteration. This is not the product ID
     *
     * @param int $pageSize
     *
     * @return null|string
     */
    public function getCurrentWidget($itemID, $pageSize = 9)
    {
        /**
         * @var $category Category
         */
        $category = $this->registry->registry('current_category');

        if (is_null($category)) {
            return null;
        }

        $category = $this->categoryRepository->get($category->getId());

        if ($category != null && $category->getCategoryWidgets() != null && $category->getCategoryWidgets() != "") {
            $curPage = $this->getRequest()->getParam('p', 1);

            if (!$curPage) {
                $curPage = 1;
            }

            $itemID = $itemID + (($curPage - 1) * $pageSize);

            $html = '';
            $widgets = $this->jsonHelper->jsonDecode($category->getCategoryWidgets());

            foreach ($widgets as $widget) {
                if ($widget["column"] == $itemID) {
                    $html .= $this->filterProvider->getBlockFilter()->filter($widget["widget"]);
                }
            }

            return $html;
        }

        return null;
    }
}
