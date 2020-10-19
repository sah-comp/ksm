<!DOCTYPE html>
<html lang="<?php echo $language ?>" class="no-js">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
	        font-size: 10pt;
        }
        h1 {
            font-size: 23pt;
        }
        h2 {
            font-size: 16pt;
        }
        p {
            margin: 0pt 0pt 10pt 0pt;
        }
        table {
            margin-top: 40pt;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        td {
            border-top: 1pt solid black;
            width: 50%;
            padding: 0;
            margin: 0;
        }
        .centered {
            text-align: center;
        }
        .senderline {
            font-size: 6pt;
            border-bottom: 0.1mm solid #000000;
        }
    </style>
</head>
<body>
<?php echo Flight::textile($text) ?>
</body>
</html>
