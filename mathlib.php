<?php
    class Math {
        function sort($array) {
            sort($array, SORT_NUMERIC);
            return $array;
        }

        // sorted array required
        function quartile($percentil, $array) {
            return $array[ceil($percentil * sizeof($array)) - 1];
        }

        // sorted array required
        function iqr($array) {
            return $this->quartile(0.75, $array) - $this->quartile(0.25, $array);
        }

        // sorted array required
        function median($array) {
            $length = sizeof($array);

            if($length % 2 == 1) return $array[($length + 1) / 2 - 1];
            else return 0.5 * ($array[$length / 2 - 1] + $array[$length / 2]);
        }

        // sorted array required
        function range($array) {
            $length = sizeof($array);

            return $array[$length - 1] - $array[0];
        }

        function mean($array) {
            $sum = 0;

            foreach($array as $number) {
                $sum += $number;
            }

            return (1 / sizeof($array)) * $sum; 
        }

        function variance($array, $mean = null) {
            $sum = 0;

            if(!isset($mean)) $mean = $this->mean($array);

            foreach($array as $number) {
                $sum += pow($number - $mean, 2);
            }

            return (1 / (sizeof($array) - 1)) *  $sum;
        }
        
        function deviation($array, $mean = null) {
            return sqrt($this->variance($array, $mean));
        }
    }
?>