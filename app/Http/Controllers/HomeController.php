<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GroupController;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

class HomeController extends Controller
{
    public function index()
    {
        $productCountByGroup = (new GroupController())->getTopLevelGroups();

        return view('layouts.app', [
            'groups' => $productCountByGroup
        ]);

    }


    public function view(Request $request, $type, $group = null, $undergroup = null)
    {

        $groups = (new GroupController())->getLevelGroups();

        //Подсчитываем общее кол-во товаров с подгруппах

        function countProductsAndSetTotals(&$array) {
            $totalCount = 0;

            // Проверяем, есть ли ключ 'products_count' и добавляем его значение к общему счетчику
            if (isset($array['products_count'])) {
                $totalCount += $array['products_count'];
            }

            // Проверяем, есть ли подгруппы и рекурсивно обрабатываем их
            if (isset($array['subgroups'])) {
                foreach ($array['subgroups'] as &$subgroup) {
                    $totalCount += countProductsAndSetTotals($subgroup); // Рекурсивный вызов
                }
            }

            // Устанавливаем общее количество товаров в текущем массиве
            $array['total_counts'] = $totalCount;

            return $totalCount;
        }


        foreach ($groups as $k => $v) {
            countProductsAndSetTotals($groups[$k]);
        }


         $result = [];
        //Пересобираем массив убирая ненужные подгруппы
        foreach ($groups as $k => $v) {
            $result[$k]['total_counts'] = $v['total_counts'];
                foreach ($v['subgroups'] as $item => $items) {
                    $result[$k]['subgroups'][$item] = ['total_counts' => $items['total_counts'], 'subgroups' => []];
                }
        }
        if($group != null && $undergroup == null) {
            $result[$type] = $groups[$type];
        }

        if($undergroup != null) {
            $result[$type]['subgroups'][$group]['subgroups'] = $groups[$type]['subgroups'][$group]['subgroups'];
        }

        return view('layouts.app', [
            'group' => $result
        ]);
    }
}
