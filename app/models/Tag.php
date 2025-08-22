<?php

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = ['name', 'slug', 'description'];

    public function getBySlug($slug)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE slug = ?", [$slug]);
    }

    public function findOrCreate($name)
    {
        $slug = $this->generateSlug($name);
        
        // Check if tag exists
        $existing = $this->db->fetchOne("SELECT * FROM {$this->table} WHERE slug = ?", [$slug]);
        
        if ($existing) {
            return $existing;
        }
        
        // Create new tag
        $tagId = $this->create([
            'name' => $name,
            'slug' => $slug,
            'description' => ''
        ]);
        
        return $this->find($tagId);
    }

    private function generateSlug($name)
    {
        $baseSlug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
        $slug = $baseSlug;
        $counter = 1;

        while ($this->db->fetchOne("SELECT id FROM {$this->table} WHERE slug = ?", [$slug])) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getPostTags($postId)
    {
        $sql = "SELECT t.* FROM {$this->table} t 
                JOIN post_tags pt ON t.id = pt.tag_id 
                WHERE pt.post_id = ?";
        return $this->db->fetchAll($sql, [$postId]);
    }
}