<?php
    require_once("mathlib.php");
    require_once("datalib.php");

    $math = new Math();
    $data = new Data();

    $img_x = 1000;
    $img_y = 500;

    header("Content-Type: image/png");

    $array = $data->str2NumberArray($_GET["set"]);
    $borders = $data->str2NumberArray($_GET["borders"]);
    $borders = $math->sort($borders);

    $sections = [];

    // add one more than length of boder array
    for($i = 0; $i < sizeof($borders); $i++) {
        array_push($sections, []);
    }

    foreach($array as $number) {
        for($i = 0; $i < sizeof($borders) - 1; $i++) {
            if($borders[$i] <= $number && $borders[$i+1] > $number) array_push($sections[$i], $number);
        }
    }

    $img = ImageCreate($img_x, $img_y);
    $white = ImageColorAllocate($img, 0xFF, 0xFF, 0xFF);

    $offset_bottom = 30;
    $offset_left = 60;
    $offset_top = 30;
    $offset_right = 30;

    $black = ImageColorAllocate($img, 0x00, 0x00, 0x00);
    $red = ImageColorAllocate($img, 0xFF, 0x00, 0x00);
    $font = "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf";
    $font_size = 10;
    $section_range_total = 0;

    $max_section_height = 0;
    $max_section_range = 0;

    for($i = 0; $i < sizeof($borders) - 1; $i++) {
        $section_range = $borders[$i + 1] - $borders[$i];
        $section_height = sizeof($sections[$i]) / sizeof($array) / $section_range;
        
        if($max_section_height < $section_height) {
            $max_section_height = $section_height;
            $max_section_range = $section_range;
        }

        $section_range_total += $section_range;
    }

    $offset_x = 0;

    $inner_width = $img_x - $offset_left - $offset_right;
    $inner_height = $img_y - $offset_bottom - $offset_top;

    for($i = 0; $i < sizeof($sections) - 1; $i++) {
        $section = $sections[$i];
        
        $section_range = $borders[$i + 1] - $borders[$i];
        $percentage = sizeof($section) / sizeof($array);

        $section_x = $offset_x + $offset_left;
        $section_y = $img_y - $offset_bottom;

        $section_width = $section_range / $section_range_total * $inner_width;
        $section_height = $percentage / $section_range * $inner_height / $max_section_height;

        $percentage_rounded = round($percentage, 2);
        
        ImageFilledRectangle($img, $section_x, $section_y, $section_x + $section_width - (($i < sizeof($sections) - 2) ? 2 : 0), $section_y - $section_height, $red);
        imagettftext($img, $font_size, 0, ceil($section_x + $section_width / 2 - strlen($percentage_rounded) * $font_size / 2), $section_y - $section_height - $font_size, $red, $font, $percentage_rounded);

        $section_border = $borders[$i];

        imagettftext($img, $font_size, 0, floor($section_x - strlen($section_border) / 2 * $font_size), $img_y - $offset_bottom + $font_size + 10, $black, $font, $section_border);
        imageline($img, $section_x , $img_y - $offset_bottom + 5, $section_x, $img_y - $offset_bottom - 5, $black);
        
        $offset_x += $section_width;
    }

    $step_count = 10;

    for($i = 0; $i <= $step_count; $i++)  {
        $x = $offset_left;
        $y_value = round($i / $step_count * $max_section_height, 3);
        $y = $img_y - $offset_bottom - $i / $step_count * $inner_height;

        imageline($img, $x - 5 , $y, $x + 5, $y, $black);

        imagettftext($img, $font_size, 0, $x - strlen($y_value) * $font_size - 5, $y + $font_size / 2, $black, $font, $y_value);
    }

    imageline($img, $offset_left + $inner_width, $img_y - $offset_bottom + 5, $offset_left + $inner_width, $img_y - $offset_bottom - 5, $black);
    imagettftext($img, $font_size, 0, $offset_left + $inner_width - strlen($section_border) / 2 * $font_size, $img_y - $offset_bottom + $font_size + 10, $black, $font, $borders[sizeof($borders) - 1]);

    imageline($img, $offset_left, $img_y - $offset_bottom, $img_x - $offset_right, $img_y - $offset_bottom, $black);
    imageline($img, $offset_left, $img_y - $offset_bottom, $offset_left, $offset_top, $black);
    
    ImagePNG($img);
?>