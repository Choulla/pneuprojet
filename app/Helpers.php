<?php 

function getPrice($price){
    return floatval($price);
}

function totalAfterReduction($total,$reduction){
    $a=$reduction/100;
    $b=1-$a;
    return $total*$b;
}