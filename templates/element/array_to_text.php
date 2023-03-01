<?php
if (!isset($array)) {
    echo "No data";
    return;
}

$formatter = function (array $arr) {
    $parts = [];
    krsort($arr);
    foreach ($arr as $key => $val) {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $parts[] = sprintf("<strong>%s:</strong> %s", $key, $val);
    }
    return join("<br>", $parts);
};

echo $formatter((array) $array);