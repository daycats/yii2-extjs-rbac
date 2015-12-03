<?php
/**
 * Created by PhpStorm.
 * User: shanli
 * Date: 2015/8/23
 * Time: 17:21
 */

namespace wsl\rbac\helpers;

/**
 * ExtJs帮助类
 *
 * @package wsl\rbac\helpers
 */
class ExtHelpers
{
    /**
     * 获取排序字段
     *
     * @param string $sort 排序字符
     * @param array $allowField 允许排序字段
     * @param array $mapped 字段映射
     * @param string $defaultOrder 默认排序
     * @return string
     */
    public static function getOrder($sort, $allowField, $mapped = [], $defaultOrder = '')
    {
        $order = $defaultOrder;
        if ($sort) {
            $orders = [];
            $sortData = json_decode($sort, true);
            foreach ($sortData as $item) {
                if (!empty($item) && isset($item['property']) && isset($item['direction'])) {
                    $fieldName = $item['property'];
                    if (isset($mapped[$fieldName])) {
                        $fieldName = $mapped[$fieldName];
                    }
                    if (in_array($fieldName, $allowField)) {
                        $order = $fieldName;
                        if ('DESC' == $item['direction']) {
                            $order .= ' DESC';
                        } else {
                            $order .= ' ASC';
                        }
                        $orders[] = $order;
                    }
                }
            }
            if ($orders) {
                $order = join(',', $orders);
            }
        }

        return $order;
    }
}