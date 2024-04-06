<?php

namespace App\Services;

class ImageService
{
    public function enregistrementImage(array $file): array
    {
        $dossier = __DIR__ . '/../../public/';
        $dossierUpload = $dossier . 'upload/';
        $taille_maxi = 100000;
        $extensions = ['.png', '.gif', '.jpg', '.jpeg'];
        $extension = strrchr($file['name'], '.');
        $taille = filesize($file['tmp_name']);
        $erreurs = [];

        if (!in_array($extension, $extensions)) {
            $erreurs[] = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg.';
        }

        if ($taille > $taille_maxi) {
            $erreurs[] = 'Le fichier est trop gros.';
        }

        if (empty($erreurs)) {
            $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', basename(str_replace(' ', '-', strtolower($file['name']))));
            if (!file_exists($dossierUpload)) {
                mkdir($dossierUpload, 0777, true);
            }
            if (move_uploaded_file($file['tmp_name'], $dossierUpload . $fichier)) {
                return [
                    'name' => $fichier,
                    'success' => true
                ];
            } else {
                return [
                    'name' => $fichier,
                    'success' => false,
                    'message' => 'Le fichier n\'a pas pu être enregistré.'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => implode(' ', $erreurs)
            ];
        }
    }
}