<?php

namespace OpenOrchestra\Elastica\SchemaGenerator;

use Elastica\Client;
use OpenOrchestra\Elastica\Factory\MappingFactory;
use OpenOrchestra\Elastica\Mapper\FieldToElasticaTypeMapper;
use OpenOrchestra\ModelInterface\Model\ContentTypeInterface;
use OpenOrchestra\ModelInterface\Model\FieldTypeInterface;

/**
 * Class ContentTypeSchemaGenerator
 */
class ContentTypeSchemaGenerator implements DocumentToElasticaSchemaGeneratorInterface
{
    protected $client;
    protected $indexName;
    protected $formMapper;
    protected $mappingFactory;

    /**
     * @param Client                    $client
     * @param FieldToElasticaTypeMapper $formMapper
     * @param string                    $indexName
     * @param MappingFactory            $mappingFactory
     */
    public function __construct(Client $client, FieldToElasticaTypeMapper $formMapper, $indexName, MappingFactory $mappingFactory)
    {
        $this->client = $client;
        $this->indexName = $indexName;
        $this->formMapper = $formMapper;
        $this->mappingFactory = $mappingFactory;
    }

    /**
     * Create a elasticSearch linked to the object
     *
     * @param ContentTypeInterface $contentType
     */
    public function createMapping($contentType)
    {
        $index = $this->client->getIndex($this->indexName);
        $type = $index->getType('content_' . $contentType->getContentTypeId());

        $mappingProperties = array(
            'id' => array('type' => 'string', 'include_in_all' => true),
            'elementId' => array('type' => 'string', 'include_in_all' => true),
            'contentId' => array('type' => 'string', 'include_in_all' => true),
            'name' => array('type' => 'string', 'include_in_all' => true),
            'siteId' => array('type' => 'string', 'include_in_all' => true),
            'language' => array('type' => 'string', 'include_in_all' => true),
            'contentType' => array('type' => 'string', 'include_in_all' => true),
        );

        /** @var FieldTypeInterface $field */
        foreach ($contentType->getFields() as $field) {
            if ($field->isSearchable()) {
                $mappingProperties['attribute_' . $field->getFieldId()] = array('type' => $this->formMapper->map($field->getType()), 'include_in_all' => false);
                $mappingProperties['attribute_' . $field->getFieldId() . '_stringValue'] = array('type' => 'string', 'include_in_all' => true);
            }
        }

        $mapping = $this->mappingFactory->create($type);
        $mapping->setProperties($mappingProperties);
        $mapping->send();
    }
}
