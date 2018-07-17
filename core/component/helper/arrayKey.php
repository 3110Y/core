<?php
namespace core\component\helper;

/**
 * Trait lowToUpper
 * @package core\helper
 */
trait arrayKey
{
    /**
     * Приведение ключей массива к необходимому полю
     *
     * @param array $array
     * @param string $field
     *
     * @return array
     */
    public static function arrayKeyToField(array $array, string $field) :array
    {
        $result = Array();

        if (!empty($array) && !empty($field)) {
            foreach ($array as $key => $value) {
                if (isset($value[$field])) {
                    $result[$value[$field]] = $value;
                }
            }
        }

        return $result;
    }


}