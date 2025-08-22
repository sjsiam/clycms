<?php

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'status',
        'author_id',
        'featured_image',
        'meta_title',
        'meta_description'
    ];

    public function getPublished()
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM {$this->table} p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.status = 'published' AND p.post_type = 'post'
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getBySlug($slug)
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM {$this->table} p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.slug = ? AND p.status = 'published'";
        return $this->db->fetchOne($sql, [$slug]);
    }

    public function getByCategory($categoryId)
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM {$this->table} p 
                JOIN users u ON p.author_id = u.id 
                JOIN post_categories pc ON p.id = pc.post_id 
                WHERE pc.category_id = ? AND p.status = 'published'
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql, [$categoryId]);
    }

    public function search($query)
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM {$this->table} p 
                JOIN users u ON p.author_id = u.id 
                WHERE (p.title LIKE ? OR p.content LIKE ?) AND p.status = 'published'
                ORDER BY p.created_at DESC";
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm]);
    }
}
