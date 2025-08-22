<?php

class Media extends Model
{
    protected $table = 'media';
    protected $fillable = [
        'filename',
        'original_filename', 
        'file_path',
        'file_size',
        'mime_type',
        'width',
        'height',
        'alt_text',
        'caption',
        'description',
        'uploaded_by'
    ];

    public function getByType($mimeType = null)
    {
        if ($mimeType) {
            $sql = "SELECT m.*, u.name as uploader_name 
                    FROM {$this->table} m 
                    LEFT JOIN users u ON m.uploaded_by = u.id 
                    WHERE m.mime_type LIKE ? 
                    ORDER BY m.created_at DESC";
            return $this->db->fetchAll($sql, [$mimeType . '%']);
        }
        
        $sql = "SELECT m.*, u.name as uploader_name 
                FROM {$this->table} m 
                LEFT JOIN users u ON m.uploaded_by = u.id 
                ORDER BY m.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function search($query)
    {
        $sql = "SELECT m.*, u.name as uploader_name 
                FROM {$this->table} m 
                LEFT JOIN users u ON m.uploaded_by = u.id 
                WHERE m.original_filename LIKE ? 
                   OR m.alt_text LIKE ? 
                   OR m.caption LIKE ? 
                   OR m.description LIKE ?
                ORDER BY m.created_at DESC";
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }

    public function uploadFile($file, $userId = null)
    {
        $uploadDir = STORAGE_PATH . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        // Validate file type
        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'application/pdf', 'text/plain', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'video/mp4', 'video/avi', 'video/mov', 'audio/mp3', 'audio/wav'
        ];

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('File type not allowed.');
        }

        // Check file size (10MB limit)
        if ($file['size'] > 10 * 1024 * 1024) {
            throw new Exception('File size too large. Maximum 10MB allowed.');
        }

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception('Failed to upload file.');
        }

        // Get image dimensions if it's an image
        $width = null;
        $height = null;
        if (strpos($file['type'], 'image/') === 0) {
            $imageInfo = getimagesize($targetPath);
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];
            }
        }

        // Save to database
        $mediaId = $this->create([
            'filename' => $filename,
            'original_filename' => $file['name'],
            'file_path' => '/storage/uploads/' . $filename,
            'file_size' => $file['size'],
            'mime_type' => $file['type'],
            'width' => $width,
            'height' => $height,
            'uploaded_by' => $userId ?: Auth::id()
        ]);

        return $this->find($mediaId);
    }

    public function deleteFile($id)
    {
        $media = $this->find($id);
        if (!$media) {
            throw new Exception('Media file not found.');
        }

        // Delete physical file
        $filePath = STORAGE_PATH . '/uploads/' . $media['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete from database
        return $this->delete($id);
    }

    public function getImageSizes($mediaId)
    {
        $media = $this->find($mediaId);
        if (!$media || strpos($media['mime_type'], 'image/') !== 0) {
            return [];
        }

        $sizes = [
            'thumbnail' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 300, 'height' => 300],
            'large' => ['width' => 1024, 'height' => 1024]
        ];

        $result = ['full' => $media['file_path']];
        
        // In a real implementation, you'd generate these sizes
        // For now, we'll just return the original image
        foreach ($sizes as $size => $dimensions) {
            $result[$size] = $media['file_path'];
        }

        return $result;
    }

    public function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}