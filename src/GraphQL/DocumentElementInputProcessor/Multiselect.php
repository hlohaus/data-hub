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
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\DataHubBundle\GraphQL\DocumentElementInputProcessor;


use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\Document\PageSnippet;

class Multiselect extends Base
{
    /**
     * @param PageSnippet $document
     * @param mixed $newValue
     * @param array $args
     * @param mixed $context
     * @param ResolveInfo $info
     */
    public function process($document, $newValue, $args, $context, ResolveInfo $info)
    {
        $tagName = $newValue['_tagName'];
        $tagType = $newValue['_tagType'];

        $text = $newValue['selections'] ?? [];

        $tag = $this->tagLoader->build($tagType);
        $tag->setName($tagName);
        $tag->setDataFromResource($text);                   // this should be at least valid for input, wysiwyg

        if (method_exists($document, 'setEditable')) {
            $document->setEditable($tagName, $tag);
        } else {
            // this one is deprecated and will be removed with pimcore 7
            $document->setElement($tagName, $tag);
        }
    }

}

