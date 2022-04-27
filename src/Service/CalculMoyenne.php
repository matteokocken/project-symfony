<?php
namespace App\Service;

class CalculMoyenne
{
    public function moyenne(array $list) {
        return array_sum($list) / count($list);
    }
}