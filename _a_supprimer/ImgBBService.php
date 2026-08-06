<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class ImgBBService
{
    protected $apiKey;
    protected $client;

    public function __construct()
    {
        $this->apiKey = config('services.imgbb.api_key');
        
        if (!$this->apiKey) {
            Log::error('ImgBB API key not configured');
            throw new Exception('ImgBB API key is not configured. Please add IMGBB_API_KEY to your .env file');
        }
        
        $this->client = new Client([
            'base_uri' => 'https://api.imgbb.com/1/',
            'timeout' => 60, // 60 secondes pour les gros fichiers
        ]);
    }

    /**
     * Upload une image vers ImgBB
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string|null URL de l'image ou null en cas d'erreur
     */
    public function upload($file)
    {
        try {
            // Vérifier que le fichier est valide
            if (!$file->isValid()) {
                Log::error('Invalid file uploaded', ['file' => $file->getClientOriginalName()]);
                return null;
            }

            // Convertir l'image en base64
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            
            // Nom du fichier sans extension
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            
            Log::info('Uploading image to ImgBB', [
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);
            
            // Envoyer la requête à ImgBB
            $response = $this->client->post('upload', [
                'form_params' => [
                    'key' => $this->apiKey,
                    'image' => $imageData,
                    'name' => $filename,
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (isset($result['success']) && $result['success'] === true) {
                $imageUrl = $result['data']['url'];
                
                Log::info('Image uploaded successfully to ImgBB', [
                    'url' => $imageUrl,
                    'filename' => $filename
                ]);
                
                return $imageUrl;
            }

            Log::error('ImgBB upload failed', ['response' => $result]);
            return null;

        } catch (Exception $e) {
            Log::error('ImgBB upload exception', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);
            return null;
        }
    }

    /**
     * Upload plusieurs images vers ImgBB
     * 
     * @param array $files Array d'UploadedFile
     * @return array URLs des images uploadées avec succès
     */
    public function uploadMultiple(array $files)
    {
        $urls = [];
        $failedCount = 0;
        
        Log::info('Starting multiple image upload', ['count' => count($files)]);
        
        foreach ($files as $index => $file) {
            $url = $this->upload($file);
            
            if ($url) {
                $urls[] = $url;
            } else {
                $failedCount++;
                Log::warning('Failed to upload image', [
                    'index' => $index,
                    'filename' => $file->getClientOriginalName()
                ]);
            }
        }
        
        Log::info('Multiple image upload completed', [
            'total' => count($files),
            'successful' => count($urls),
            'failed' => $failedCount
        ]);
        
        return $urls;
    }

    /**
     * Vérifie si l'API key est configurée
     * 
     * @return bool
     */
    public function isConfigured()
    {
        return !empty($this->apiKey);
    }

    /**
     * Teste la connexion à l'API ImgBB
     * 
     * @return bool
     */
    public function testConnection()
    {
        try {
            // Créer une petite image de test (1x1 pixel transparent)
            $testImage = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
            
            $response = $this->client->post('upload', [
                'form_params' => [
                    'key' => $this->apiKey,
                    'image' => $testImage,
                    'name' => 'test',
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            
            return isset($result['success']) && $result['success'] === true;
            
        } catch (Exception $e) {
            Log::error('ImgBB connection test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}