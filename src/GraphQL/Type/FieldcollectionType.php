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
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace Pimcore\Bundle\DataHubBundle\GraphQL\Type;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\UnionType;
use Pimcore\Bundle\DataHubBundle\GraphQL\FieldcollectionDecriptor;
use Pimcore\Bundle\DataHubBundle\GraphQL\Service;
use Pimcore\Bundle\DataHubBundle\GraphQL\Traits\ServiceTrait;
use Pimcore\Cache\Runtime;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FieldcollectionType extends UnionType implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    use ServiceTrait;


    protected $types;


    /**
     * FieldcollectionType constructor.
     * @param Service $graphQlService
     * @param array $config
     */
    public function __construct(Service $graphQlService, $config = [])
    {
        $this->types = $config["types"];
        $this->setGraphQLService($graphQlService);

        parent::__construct($config);
    }


    /**
     * @return array|\GraphQL\Type\Definition\ObjectType[]
     *
     * @throws \Exception
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @inheritdoc
     */
    public function resolveType($element, $context, ResolveInfo $info)
    {
        if ($element instanceof FieldcollectionDecriptor) {
            $fcName = $element["__fcType"];
            $fcKey = "graphql_fieldcollection_" . $fcName;
            $type = Runtime::get($fcKey);
            return $type;
        }

        return null;
    }

}
