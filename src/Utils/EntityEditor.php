<?php

namespace App\Utils;

use Doctrine\Common\Inflector\Inflector;

class EntityEditor
{
    private $entity;

    private $editable;

    /**
     * @param $entity
     * @param array $editable
     */
    public function __construct(&$entity, array $editable)
    {
        $this->entity = $entity;
        $this->editable = $editable;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getSetter(string $key): string
    {
        return 'set' . Inflector::classify($key);
    }

    /**
     * @param array $props
     * @return boolean
     */
    public function update(array $props): bool
    {
        $updated = false;

        foreach ($props as $key => $val) {
            if (in_array($key, $this->editable)) {
                $setter = $this->getSetter($key);
                call_user_func(array($this->entity, $setter), $val);
                $updated = true;
            }
        }

        return $updated;
    }
}
