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
        'post_type',
        'featured_image',
        'meta_title',
        'meta_description'
    ];

    public function getPublished($post_type = 'post')
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM {$this->table} p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.status = 'published' AND p.post_type = ?
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql, [$post_type]);
    }

    public function getBySlug($slug, $post_type = 'post')
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM {$this->table} p 
                JOIN users u ON p.author_id = u.id 
                WHERE p.slug = ? AND p.post_type = ? AND p.status = 'published'";
        return $this->db->fetchOne($sql, [$slug, $post_type]);
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

    public function getByTag($tagId)
    {
        $sql = "SELECT p.*, u.name as author_name 
                FROM {$this->table} p 
                JOIN users u ON p.author_id = u.id 
                JOIN post_tags pt ON p.id = pt.post_id 
                WHERE pt.tag_id = ? AND p.status = 'published'
                ORDER BY p.created_at DESC";
        return $this->db->fetchAll($sql, [$tagId]);
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
