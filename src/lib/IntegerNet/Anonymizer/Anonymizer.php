<?php
/**
 * Created by PhpStorm.
 * User: fs
 * Date: 16.03.2015
 * Time: 18:31
 */

namespace IntegerNet\Anonymizer;


use IntegerNet\Anonymizer\Implementor\AnonymizableEntity;

class Anonymizer
{
    const __CLASS = __CLASS__;
    /**
     * @var Provider
     */
    private $provider;

    /**
     * @param Provider $provider
     * @param string $locale
     */
    function __construct(Provider $provider = null, $locale = null)
    {
        if ($provider === null) {
            $provider = new Provider();
        }
        $this->provider = $provider;
        $this->provider->initialize($locale);
    }

    /**
     * @param AnonymizableEntity[] $inputData
     */
    public function anonymize(array $inputData)
    {
        foreach ($inputData as $entity) {
            foreach ($entity->getValues() as $value) {
                $value->setValue($this->provider->getFakerData(
                    $value->getFakerFormatter(), $this->_getFieldIdentifier($entity, $value), $value->isUnique()));
            }
        }
    }

    /**
     * Returns identifier for a field, based on entity and current value. This is used to map real data to fake
     * data in the same way for each unique entity identifier (i.e. customer id)
     *
     * @param AnonymizableEntity $entity
     * @param AnonymizableValue $value
     * @return string
     */
    private function _getFieldIdentifier(AnonymizableEntity $entity, AnonymizableValue $value)
    {
        return sprintf('%s|%s', $entity->getIdentifier(), join('', (array)$value->getValue()));
    }

    public function resetUniqueGenerator()
    {
        $this->provider->resetUniqueGenerator();
    }
}