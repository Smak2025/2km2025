<?php

// Путь к исходному изображению
if (isset($_GET['src'])) {
    $imagePath = urldecode($_GET['src']);

    // Загружаем изображение
    $image = false;
    if (preg_match("/.*\.(jpg|jpeg)$/", $imagePath) === 1){
        $image = imagecreatefromjpeg($imagePath);
    }
    if (preg_match("/.*\.(png)$/", $imagePath) === 1){
        $image = imagecreatefrompng($imagePath);
    }
    // Проверяем, успешно ли изображение загружено
    if ($image) {
        //list($width, $height) = getimagesize($imagePath);
        //$new_image = imagecreatetruecolor(800, 600);
        //imagecopyresampled($new_image, $image, 0, 0, 0, 0, 800, 500, $width, $height);
        // Задаём цвет текста (RGB)
        $textColor = imagecolorallocate($image, 255, 255, 0);

        // Путь к файлу шрифта (необязательно, по умолчанию используется встроенный)
        $fontFile = 'fonts/MISTRAL.TTF'; // Убедитесь, что файл шрифта доступен


        // Координаты для вывода текста (x, y)
        $x = 30;
        $y = 350;
        $d = (int)$_GET['d'];
        if ($d !== 0) {
            // Текст, который нужно добавить
            $text = "Только сегодня! Акция!!!\n-$d% на это \"авто\"!!!";
            imagettftext($image, 55, 30, $x, $y, $textColor, $fontFile, $text);
        }
        // Выводим текст на изображение


        // Устанавливаем заголовок, чтобы браузер понял, что получает изображение
        header('Content-Type: image/jpeg');

        // Выводим изображение в браузер
        imagejpeg($image);

        // Освобождаем память
        //imagedestroy($new_image);
        imagedestroy($image);
    }
}
?>