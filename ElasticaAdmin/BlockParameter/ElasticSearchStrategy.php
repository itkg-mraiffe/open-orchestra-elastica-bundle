<?php

namespace OpenOrchestra\ElasticaAdmin\BlockParameter;

use OpenOrchestra\Backoffice\BlockParameter\BlockParameterInterface;
use OpenOrchestra\ModelInterface\Model\BlockInterface;

/**
 * Class ElasticSearchStrategy
 */
class ElasticSearchStrategy implements BlockParameterInterface
{
    /**
     * @param BlockInterface $block
     *
     * @return bool
     */
    public function support(BlockInterface $block)
    {
        return 'elastica_search' === $block->getComponent();
    }

    /**
     * @return array
     */
    public function getBlockParameter()
    {
        return array('request.elastica_search');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'elastica_search';
    }
}
