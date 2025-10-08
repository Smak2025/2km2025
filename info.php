<?php

function generate(): Generator
{
    $a = 0;
    $b = 1;
    //$results = [$a, $b];
    yield $a;
    yield $b;
    for ($i = 1; $i <= 10; $i++) {
        $b += $a;
        $a = $b - $a;
        yield $b;
        //$results[] = $b;
    }
    //return $results;
}

$results = generate();
foreach ($results as $result) {
    print($result);
    print("<br>\n");
}
