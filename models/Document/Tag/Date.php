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

namespace Pimcore\Model\Document\Tag;

use Pimcore\Model\Document\Editable\Date as EditableDate;

@trigger_error(sprintf('Class "%s" is deprecated since v6.7 and will be removed in 7. Use "%s" instead.', Dao::class, EditableDate::class), E_USER_DEPRECATED);

class_exists(EditableDate::class);

if (false) {
    /**
     * @deprecated use \Pimcore\Model\Document\Editable\Date instead.
     */
    class Date extends EditableDate
    {
    }
}
