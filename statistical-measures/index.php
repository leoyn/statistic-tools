<?php
    require_once("../lib/math.php");
    require_once("../lib/data.php");

    $math = new Math();
    $data = new Data();


    $array = $data->str2NumberArray($_GET["set"]);
    $borders = $data->str2NumberArray($_GET["borders"]);
    $array = $math->sort($array);

    function rutime($ru, $rus, $index) {
        return ($ru["ru_" . $index . ".tv_sec"]*1000 + intval($ru["ru_" . $index . ".tv_usec"]/1000))
         -  ($rus["ru_" . $index . ".tv_sec"]*1000 + intval($rus["ru_" . $index . ".tv_usec"]/1000));
    }
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <title>Statistical Measures Calculator</title>
        <style type="text/css">
            table th td;:first-child {
                text-align: right;
            }

            #set {
                font-size: 24px;
            }

            #set input {
                font-size: 24px;
                border: none;
                border-bottom: 1px solid #000;
            }

            input[type="submit"] {
                background-color: #6ddf81;
                font-size: 24px;
                border: none;
                border-radius: 5px;
                padding: 5px;
                padding-left: 15px;
                padding-right: 15px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <h1>Statistical Measures Calculator</h1>
        <form method="GET">
            <p id="set">Set = {<input value="<?php $data->pa($array) ?>" name="set" placeholder="1.5, 3.1, 4.3">}</p>
            <p id="set">Borders = {<input value="<?php $data->pa($borders) ?>" name="borders" placeholder="2">}</p>
            <p>Use comma-seperated (,) set. Max length of set is limited to 100 numbers per request.</p>
            <input type="submit" value="calculate">
        </form>
<?php
    if(sizeof($array) > 0) {
        $mean = $math->mean($array);
?>
        <table border="1px solid #000">
            <tr>
                <th>Name</th>
                <th>Calculation</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>x (mean) =</td>
                <td></td>
                <td><?php $data->p($mean) ?></td>
            </tr>
            <tr>
                <td>median   =</td>
                <td></td>
                <td><?php $data->p($math->median($array)) ?></td>
            </tr>
            <tr>
                <td>r (range) =</td>
                <td><?php $data->p($array[sizeof($array) - 1]) ?> - <?php $data->p($array[0]) ?> =</td>
                <td><?php $data->p($math->range($array)) ?></td>
            </tr>
            <tr>
                <td>s^2 (variance) =</td>
                <td></td>
                <td><?php $data->p($math->variance($array, $mean)) ?></td>
            </tr>
            <tr>
                <td>s (standard deviation) =</td>
                <td></td>
                <td><?php $data->p($math->deviation($array, $mean)) ?></td>
            </tr>
            <tr>
                <td>IQR (Interquartile Range) =</td>
                <td><?php $data->p($math->quartile(0.75, $array)) ?> - <?php $data->p($math->quartile(0.25, $array)) ?> =</td>
                <td><?php $data->p($math->iqr($array)) ?></td>
            </tr>
        </table>
        <h2>Histogram</h2>
        <img src="histogram.php?set=<?php $data->pa($array) ?>&borders=<?php $data->pa($borders) ?>">
<?php
    }
?>
    </body>
</html>