<?php

class ImageService
{
    private static array $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    private static int $maxSize = 2 * 1024 * 1024; // 2MB

    public static function uploadImage(array $file, string $uploadDir, ?string $previousImage = null):string
    {
        if (!isset($file) || $file['error'] !== 0) {
            throw new Exception("Error al subir la imagen.");
        }

        if ($file['size'] > self::$maxSize) {
            throw new Exception("La imagen excede el tamaño permitido.");
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!array_key_exists($mime, self::$allowedTypes)) {
            throw new Exception("Formato de imagen no permitido.");
        }

        $extension = self::$allowedTypes[$mime];
        $newName = bin2hex(random_bytes(16)).".".$extension;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = rtrim($uploadDir, '/') . '/' . $newName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("No se pudo guardar la imagen.");
        }

        if ($previousImage) {
            $previousPath = rtrim($uploadDir, '/') . '/' . $previousImage;
            if (file_exists($previousPath)) {
                unlink($previousPath);
            }
        }

        return $newName;
    }

    public static function getImage(?string $imageName, string $directory, string $placeholder = "../images/placeholder.png"):string {
        if (!$imageName) {
            return $placeholder;
        }

        $path = $directory.$imageName;

        if (!file_exists($path)) {
            return $placeholder;
        }

        return $path;
    }
}

?>