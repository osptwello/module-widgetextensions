<?php
/**
 * Created by PhpStorm.
 * User: stijn
 * Date: 4/9/18
 * Time: 1:42 PM
 */

namespace Riff\WidgetExtensions\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Riff\WidgetExtensions\Block\Adminhtml\Category\WidgetTable;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Catalog\Model\Category;
use \Magento\Eav\Setup\EavSetupFactory;
use Riff\WidgetExtensions\Model\Entity\Attribute\Backend\WidgetsBackend;

/**
 * Class UpgradeData
 *
 * @package Riff\WidgetExtensions\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * UpgradeData constructor.
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Upgrades data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     *
     * @return void
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $eavSetup->addAttribute(Category::ENTITY, 'category_widgets', [
                    'type' => 'text',
                    'label' => 'Category Widgets',
                    'input' => 'text',
                    'input_renderer' => WidgetTable::class,
                    'backend' => WidgetsBackend::class,
                    'required' => false,
                    'sort_order' => 5,
                    'global' => ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'General Information',
                ]);
        }
    }
}
