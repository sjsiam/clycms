<?php

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    public function getWithPostCount()
    {
        $sql = "SELECT c.*, COUNT(pc.post_id) as post_count 
                FROM {$this->table} c 
                LEFT JOIN post_categories pc ON c.id = pc.category_id 
                GROUP BY c.id 
                ORDER BY c.name";
        return $this->db->fetchAll($sql);
    }

    public function getBySlug($slug)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE slug = ?", [$slug]);
    }
}
