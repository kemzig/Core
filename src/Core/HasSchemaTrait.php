<?php

namespace Runn\Core;

use Runn\Reflection\ReflectionHelpers;

/**
 * Basic trait for classes that have data schema
 *
 * Trait HasSchemaTrait
 * @package Core
 *
 * @implements \Runn\Core\HasSchemaInterface
 */
trait HasSchemaTrait
    //implements HasSchemaInterface
{

    /*protected static $schema;*/

    /**
     * @return iterable
     */
    public static function getSchema(): iterable
    {
        return static::$schema;
    }

    /**
     * @param iterable $schema
     * @return $this
     */
    public function fromSchema(iterable $schema = null)
    {
        $data = [];
        if (null !== $schema) {
            foreach ($schema as $key => $def)
            {
                if (!empty($def['class'])) {

                    $class = $def['class'];
                    unset($def['class']);

                    // check if $def has only digital keys
                    if (ctype_digit(implode('', array_keys($def)))) {
                        $data[$key] = new $class(...array_values($def));
                        // or not - it has string keys?
                    } else {
                        $ctor = ReflectionHelpers::getClassMethodArgs($class, '__construct');
                        $args = ReflectionHelpers::prepareArgs($ctor, $def);
                        $data[$key] = new $class(...array_values($args));
                    }
                } else {
                    $data[$key] = $def;
                }
            }
        }
        $this->fromArray($data);
        return $this;
    }

}