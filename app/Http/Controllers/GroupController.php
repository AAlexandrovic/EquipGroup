<?php

namespace App\Http\Controllers;

use App\Models\Group;

class GroupController extends Controller
{
    public $underGroups = [
        'Электроника' => [4 => 'Телефоны и смарт-часы', 5 => 'Компьютеры и комплектующие'],
        'Одежда' => [6 => 'Женщинам', 7=> 'Мужчинам'],
        'Бытовая техника' => [9 => 'Крупная бытовая техника', 10 => 'Встраиваемая бытовая техника']
    ];

    public function getTopLevelGroups()
    {
        // Получаем все группы верхнего уровня
        $groups = Group::where('id_parent', 0)->get()->toArray();

        $productCount = [];

        foreach ($groups as $group) {
            if ($group['name'] == 'Электроника') {
                $productCount[$group['name']] = [4 => 'Телефоны и смарт-часы', 5 => 'Компьютеры и комплектующие'];
            }
            if($group['name'] == 'Одежда') {
                $productCount[$group['name']] = [6 => 'Женщинам', 7 => 'Мужчинам'];
            }
            if($group['name'] == 'Бытовая техника') {
                $productCount[$group['name']] = [9 => 'Крупная бытовая техника', 10 => 'Встраиваемая бытовая техника'];
            }
        }

        // Создаем массив для хранения результатов
        $productCounts = [];

        foreach ($productCount as $value) {
            foreach ($value as $key => $item) {
                // Загружаем группу с её подгруппами и продуктами
                $group = Group::with('subgroups.products')->find($key);

                // Считаем количество продуктов в основной группе
                $productCountInGroup = $group->products->count(); // Количество продуктов в текущей группе

                // Рекурсивно считаем количество продуктов в подгруппах
                $productCountInSubgroups = $this->countProductsInSubgroups($group->subgroups);

                // Суммируем количество продуктов
                $totalProducts = $productCountInGroup + $productCountInSubgroups;

                // Записываем общее количество продуктов в массив
                $productCounts[$group->name] = $totalProducts;
            }
        }

        $result = [];
        foreach ($this->underGroups as $key => $groups){
            foreach ($groups as $group) {
                $result[$key][] = $productCounts[$group];
            }
            $result[$key] = array_sum($result[$key]);
        }

        return $result;
    }

    public function countProductsInSubgroups($subgroups)
    {
        $totalProducts = 0; // Счетчик для общего количества продуктов в подгруппах

        foreach ($subgroups as $subgroup) {
            // Считаем количество продуктов в подгруппе
            $productCountInSubgroup = $subgroup->products->count();
            $totalProducts += $productCountInSubgroup; // Суммируем

            // Если есть подгруппы, вызываем рекурсивно и добавляем к общему количеству
            if ($subgroup->subgroups->isNotEmpty()) {
                $totalProducts += $this->countProductsInSubgroups($subgroup->subgroups);
            }
        }

        return $totalProducts; // Возвращаем общее количество продуктов в подгруппах
    }

    public function getLevelGroups()
    {
        $result = [];

            $mainGroup = Group::with('subgroups.products')
                ->whereIn('name', ['Электроника', 'Одежда', 'Бытовая техника'])
            ->get();

            if ($mainGroup) {
                $mainGroupArray = $mainGroup->toArray();
                    foreach ($mainGroupArray as $key => $value) {
                        $result[$value['name']] = [ 'subgroups' => $this->processGroup($value)];
                    }

            }


        return $result;
    }

    private function processGroup($groupArray)
    {
        $result = [];

        foreach ($groupArray['subgroups'] as $subgroup) {
            $subgroupData = Group::with('subgroups.products')->find($subgroup['id']);

            if ($subgroupData) {
                $result[$subgroup['name']] = [
                    'products_count' => $subgroupData->products->count(), // Количество продуктов в текущей подгруппе
                ];

                // Проверяем наличие подгрупп и рекурсивно обрабатываем их
                if (isset($subgroupData['subgroups']) && !empty($subgroupData['subgroups'])) {
                    $subgroupResult = $this->processGroup($subgroupData); // Рекурсивный вызов
                    $result[$subgroup['name']]['subgroups'] = $subgroupResult; // Добавляем подгруппы к текущей подгруппе
                }

            }
        }

        return $result;
    }

}
