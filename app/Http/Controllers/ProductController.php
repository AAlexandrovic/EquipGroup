<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $sortBy = $request->get('sort_by', 'name'); // По умолчанию сортировка по имени
        $order = $request->get('order', 'asc'); // По умолчанию по возрастанию

        if($request['sort_by'] == 'name') {
           $products = Product::join('prices', 'prices.id_product', '=', 'products.id')
                ->select('products.*', 'prices.price')
                ->orderBy($sortBy, $order)
                ->paginate(6);
        }
        if($request['sort_by'] == 'price') {
            $products = Product::join('prices', 'prices.id_product', '=', 'products.id')
                ->select('products.*', 'prices.price')
                ->orderBy($sortBy, $order)
                ->paginate(6);
        }


        return $products;
    }

    public function getProducts(Request $request, $type, $subgroup = null, $undergroup = null)
    {
        // Функция для добавления всех подгрупп товаров
        function getSubgroupsWithProducts($parentId)
        {
            // Получаем подгруппы по идентификатору родительской группы
            $groups = Group::with('products')->where('id_parent', $parentId)->get();
            $result = [];

            foreach ($groups as $group) {
                // Вытаскиваем группу и её продукты
                $groupData = $group->toArray();
                $groupData['subgroups'] = getSubgroupsWithProducts($group->id);
                $result[] = $groupData;
            }

            return $result;
        }

        //Функция для получения всех id_group товаров
        function getIds($array) {
            $ids = [];

            foreach ($array as $item) {
                if (isset($item['id_group'])) {
                    if($item['id_group'] > 10) {
                        $ids[] = $item['id_group'];
                    }
                }

                // Проверяем, есть ли вложенные массивы
                if (is_array($item)) {
                    $ids = array_merge($ids, getIds($item)); // Рекурсивный вызов
                }
            }

            return $ids;
        }

        //Проверяем какие из параметров = null и берём последний из них =>
        $arrayType = [0 => $type, 1 => $subgroup, 2=> $undergroup];

        $arrayType = array_reverse($arrayType);

        //Проходим массив в обратном порядке =>
        foreach ($arrayType as $i => $v)
        {
            if($v != null){
                $subgroup = $v;
                break;
            }

        }

        $sortBy = $request->get('sort_by', 'name'); // По умолчанию сортировка по имени
        $order = $request->get('order', 'asc'); // По умолчанию по возрастанию
        if($request->get('products') == null) {
             // Получаем идентификатор главной группы по типу
             $productsId = Group::where('name', $subgroup)->value('id');

             // Запускаем рекурсивную функцию для получения подгрупп и продуктов
             $result =  getSubgroupsWithProducts($productsId);

            $result = getIds($result);
            $result = array_unique($result);

            $result = Product::whereIn('id_group', $result)
                ->join('prices', 'prices.id_product', '=', 'products.id')
                ->select('products.*', 'prices.price')
                ->orderBy($sortBy , $order)
                ->paginate(6);

         } else {
            $result = Group::where('name', $request->get('products'))->value('id');
            $result = Product::where('id_group', '=' ,$result)
                ->join('prices', 'prices.id_product', '=', 'products.id')
                ->select('products.*', 'prices.price')
                ->orderBy($sortBy, $order)
                ->paginate(6);
        }


        return $result;
    }

    public function view($id)
    {
        //Получаем все подгруппы продукта
        function getBreath($groupId){
            $groups = Group::where('id', $groupId)->get();

            $result = [];

            foreach ($groups as $group) {
                $groupData = $group->toArray();
                $groupData['subgroups'] = getBreath($group->id_parent);
                $result = $groupData;
            }

            return $result;
        }

        function getName($array)
        {
            $result = [];

            foreach ($array as $item) {
                if (isset($item['name'])) {

                        $result[] = $item['name'];
                }

                // Проверяем, есть ли вложенные массивы
                if (is_array($item)) {
                    $result = array_merge($result, getName($item)); // Рекурсивный вызов
                }
            }

            return $result;
        }

        $product = Product::with('prices')->where('id','=', $id)->get()->toArray();
        $group_id = $product[0]['id_group'];

        $breath = Group::where('id', '=', $group_id)->get()->toArray();
        foreach ($breath as $key => $value) {
            $breath[$key]['subgroups'] = getBreath($value['id_parent']);
        }
        //Получаем только наименования групп
        $breath = getName($breath);
        $breath = array_reverse($breath);

        return view('product.view', [
            'product' => $product,
            'breath' => $breath,
            'count' => count($breath)
        ]);
    }
}
