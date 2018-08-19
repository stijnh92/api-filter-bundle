<?php
/**
 * Created by PhpStorm.
 * User: nelis
 * Date: 8/9/2018
 * Time: 10:25 PM
 */

namespace Monter\ApiFilterBundle\Filter;


use Monter\ApiFilterBundle\Annotation\ApiFilter;
use Monter\ApiFilterBundle\InvalidValueException;
use Monter\ApiFilterBundle\Parameter\Collection;
use Monter\ApiFilterBundle\Parameter\Command;

class BooleanFilter implements Filter
{
    public function apply(string $targetTableAlias, Collection $parameterCollection, ApiFilter $apiFilter, array $configs = []): string
    {
        $response = '';

        $parameter = $parameterCollection->getUnusedByName($apiFilter->id);
        if(null === $parameter) {
            return $response;
        }

        /** @var Command $command */
        $command = $parameter->getFirstCommand();

        // define value
        $value = $command->getValue();
        if($value === 'true' || $value === true || $value === '1' || $value === 1) {
            $value = 1;
        } else if($value === 'false' || $value === false || $value === '0' || $value === 0) {
            $value = 0;
        } else {
            throw new InvalidValueException(sprintf('Invalid value used for query parameter \'%s\'.', $apiFilter->id));
        }

        // create response
        $response =  sprintf('%s.%s=%b', $targetTableAlias, $apiFilter->id, $value);
        $parameter->setUsed(true);

        return $response;
    }
}