<?php

/*
 * Collection Library for Yii2
 *
 * @link      https://github.com/hiqdev/yii2-collection
 * @package   yii2-collection
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015, HiQDev (https://hiqdev.com/)
 */

namespace hiqdev\php\collection;

class Helper
{
    /**
     * Recursive safe merge.
     * Based on Yii2 yii\helpers\BaseArrayHelper::merge
     *
     * Merges two or more arrays into one recursively.
     * If each array has an element with the same string key value, the latter
     * will overwrite the former (different from array_merge_recursive).
     * Recursive merging will be conducted if both arrays have an element of array
     * type and are having the same key.
     * For integer-keyed elements, the elements from the latter array will                    
     * be appended to the former array.
     *
     * @param array $a array to be merged to
     * @param array $b array to be merged from
     * @return array the merged array
     */
    public static function merge($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);
        foreach ($args as $items) {
            if (!is_array($items)) {
                continue;
            }
            foreach ($items as $k => $v) {
                if (is_int($k)) {
                    if (isset($res[$k])) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }

    /**
     * Inserts items in front of array.
     * rough method: unset and then set, think of better
     */
    public static function insertLast(array $array, array $items)
    {
        foreach ($items as $k => $v) {
            unset($array[$k]);
        }
        foreach ($items as $k => $v) {
            $array[$k] = $v;
        }

        return $array;
    }

    /**
     * Inserts items in front of array.
     * rough method: unset and then set, think of better
     */
    public static function insertFirst(array $array, array $items)
    {
        foreach ($items as $k => $v) {
            unset($array[$k]);
        }
        $array = array_merge($items, $array);

        return $array;
    }

    /**
     * Inserts items inside of array.
     * rough method: unset and then set, think of better
     *
     * @param array        $array source array
     * @param array        $items array of items.
     * @param string|array $where where to insert
     *
     * @return array new items list
     *
     * @see add()
     */
    public static function insertInside(array $array, $items, $where)
    {
        foreach ($items as $k => $v) {
            unset($array[$k]);
        }
        $before = self::prepareWhere($array, $where['before']);
        $after  = self::prepareWhere($array, $where['after']);
        $new    = [];
        $found  = false;
        /// TODO think of realizing it better
        foreach ($array as $k => $v) {
            if (!$found && $k === $before) {
                foreach ($items as $i => $c) {
                    $new[$i] = $c;
                }
                $found = true;
            }
            $new[$k] = $v;
            if (!$found && $k === $after) {
                foreach ($items as $i => $c) {
                    $new[$i] = $c;
                }
                $found = true;
            }
        }
        if (!$found) {
            foreach ($items as $i => $c) {
                $new[$i] = $c;
            }
        }

        return $new;
    }

    /**
     * Internal function to prepare where list for insertInside.
     *
     * @param array        $array source array
     * @param array|string $list array to convert
     *
     * @return array
     */
    protected static function prepareWhere(array $array, $list)
    {
        if (!is_array($list)) {
            $list = [$list];
        }
        foreach ($list as $v) {
            if (array_key_exists($v, $array)) {
                return $v;
            }
        }

        return null;
    }

}
