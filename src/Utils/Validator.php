<?php

namespace App\Utils;

use Symfony\Component\Validator\Validator\ValidatorInterface as Valid;

/**
 * Class Validator
 * @package App\Utils
 */
class Validator
{
    /**
     * @var Valid $validator
     */
    private $validator;

    /**
     * Validator constructor.
     * @param Valid $validator
     */
    public function __construct(Valid $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $entity
     * @return array|null
     */
    public function toArray($entity):? array
    {
        $errors = $this->validator->validate($entity);
        if ($errors->count() == 0) {
            return null;
        }
        /**
         * @var \ArrayIterator $iterator
         */
        $iterator = $errors->getIterator();

        for ($arrayErrors = []; $iterator->valid(); $iterator->next()) {
            $data = $iterator->current();
            $arrayErrors[] = [
                'field' => $data->getInvalidValue(),
                'message' => $data->getMessage()
            ];
        }

        return $arrayErrors;
    }
}