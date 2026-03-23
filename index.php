<?php require __DIR__ . '/chopper.php' ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Picture Chopper Demo</title>
    <style>body { margin: 1rem; background: #111; display: flex; flex-wrap: wrap; gap: 1rem }</style>
</head>
<body>
    <?php for ($i = 0; $i < 6; $i++) echo picture_chopper('https://picsum.photos/800/600', 5) ?>
</body>
</html>
