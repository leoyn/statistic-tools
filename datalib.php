<?php

    class Data {
        function str2NumberArray($str) {
            $array = explode(",", $str, 100);
            $numberArray = [];

            foreach($array as $number) {
                if(strlen($number) > 0) array_push($numberArray, floatval($number));
            }

            return $numberArray;
        }

        function p($str) {
            echo htmlspecialchars($str);
        }

        function pa($array) {
            if(sizeof($array) > 0) echo $this->p(join(", ", $array));
        }
    }

?>