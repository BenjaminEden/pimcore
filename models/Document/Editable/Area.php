<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @category   Pimcore
 * @package    Document
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Model\Document\Editable;

use Pimcore\Document\Editable\Block\BlockName;
use Pimcore\Model;
use Pimcore\Tool\HtmlUtils;

/**
 * @method \Pimcore\Model\Document\Editable\Dao getDao()
 */
class Area extends Model\Document\Editable
{
    /**
     * @see EditableInterface::getType
     *
     * @return string
     */
    public function getType()
    {
        return 'area';
    }

    /**
     * @see EditableInterface::getData
     *
     * @return mixed
     */
    public function getData()
    {
        return null;
    }

    /**
     * @see EditableInterface::admin
     *
     * @return void
     */
    public function admin()
    {
        $options = $this->getEditmodeOptions();
        $this->outputEditmodeOptions($options);

        $attributes = $this->getEditmodeElementAttributes($options);
        $attributeString = HtmlUtils::assembleAttributeString($attributes);

        $this->outputEditmode('<div ' . $attributeString . '>');

        $this->frontend();

        $this->outputEditmode('</div>');
    }

    /**
     * @see EditableInterface::frontend
     *
     * @return void
     */
    public function frontend()
    {
        $options = $this->getOptions();

        // TODO inject area handler via DI when tags are built through container
        $tagHandler = \Pimcore::getContainer()->get('pimcore.document.tag.handler');

        // don't show disabled bricks
        if (!$tagHandler->isBrickEnabled($this, $options['type'] && $options['dontCheckEnabled'] != true)) {
            return;
        }

        // push current block name
        $blockState = $this->getBlockState();
        $blockState->pushBlock(BlockName::createFromTag($this));

        // create info object and assign it to the view
        $info = null;
        try {
            $info = new Area\Info();
            $info->setId($options['type']);
            $info->setEditable($this);
            $info->setIndex(0);
        } catch (\Exception $e) {
            $info = null;
        }

        // start at first index
        $blockState->pushIndex(1);

        $params = [];
        if (is_array($options['params']) && array_key_exists($options['type'], $options['params'])) {
            if (is_array($options['params'][$options['type']])) {
                $params = $options['params'][$options['type']];
            }
        }

        $info->setParams($params);

        $tagHandler->renderAreaFrontend($info);

        // remove current block and index from stack
        $blockState->popIndex();
        $blockState->popBlock();
    }

    /**
     * @see EditableInterface::setDataFromResource
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setDataFromResource($data)
    {
        return $this;
    }

    /**
     * @see EditableInterface::setDataFromEditmode
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setDataFromEditmode($data)
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return false;
    }

    /**
     * Gets an element from the referenced brick. E.g. if you have an area "myArea" which defines "gallery-single-images"
     * as used areabrick and this areabrick defines a block "gallery", you can use $area->getElement('gallery') to get
     * an instance of the block element.
     *
     * @param string $name
     *
     * @return Model\Document\Editable
     */
    public function getElement(string $name)
    {
        $document = $this->getDocument();
        $namingStrategy = \Pimcore::getContainer()->get('pimcore.document.tag.naming.strategy');

        $parentBlockNames = $this->getParentBlockNames();
        $parentBlockNames[] = $this->getName();

        $id = $namingStrategy->buildChildElementTagName($name, 'area', $parentBlockNames, 1);
        $editable = $document->getEditable($id);

        if ($editable) {
            $editable->setParentBlockNames($parentBlockNames);
        }

        return $editable;
    }
}

class_alias(Area::class, 'Pimcore\Model\Document\Tag\Area');
