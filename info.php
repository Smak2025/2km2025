<?php

//function generate(): Generator
//{
//    $a = 0;
//    $b = 1;
//    //$results = [$a, $b];
//    yield $a;
//    yield $b;
//    for ($i = 1; $i <= 10; $i++) {
//        $b += $a;
//        $a = $b - $a;
//        yield $b;
//        //$results[] = $b;
//    }
//    //return $results;
//}
//
//$results = generate();
//foreach ($results as $result) {
//    print($result);
//    print("<br>\n");
//}
eval('$a = 5;');
//print $a;
$b = 3;
$c = 8;
$expression = '3 + 5 == 8';
//5=5
eval('$r = '.$expression.';');
print $r;