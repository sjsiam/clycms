<?php
$is_edit = isset($post) && $post;
$page_title = $is_edit ? 'Edit Post' : 'Create New Post';
$page_subtitle = $is_edit ? 'Update your existing post' : 'Write and publish a new post';
$page_description = $is_edit ? 'Edit post - ' . htmlspecialchars($post['title']) : 'Create a new blog post';
$include_editor = true;
include dirname(__DIR__) . '/includes/header.php';
?>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="row">
    <div class="col-md-8">
        <!-- Main Content -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <input type="text"
                        class="form-control form-control-lg"
                        name="title"
                        placeholder="Enter post title..."
                        value="<?= htmlspecialchars($post['title'] ?? '') ?>"
                        required
                        <?= !$is_edit ? 'oninput="generateSlug(this.value)"' : '' ?>>
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug (URL)</label>
                    <div class="input-group">
                        <span class="input-group-text"><?= Config::get('app.url') ?>/post/</span>
                        <input type="text"
                            class="form-control"
                            name="slug"
                            id="slug"
                            value="<?= htmlspecialchars($post['slug'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" id="content" class="rich-editor"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea class="form-control"
                        name="excerpt"
                        id="excerpt"
                        rows="3"
                        placeholder="Brief description of the post..."><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
                    <div class="form-text">Optional. If left empty, it will be auto-generated from content.</div>
                </div>
            </div>
        </div>

        <!-- SEO Section -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-search"></i> SEO Settings</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text"
                        class="form-control"
                        name="meta_title"
                        id="meta_title"
                        value="<?= htmlspecialchars($post['meta_title'] ?? '') ?>"
                        placeholder="SEO title for search engines">
                    <div class="form-text">Recommended: 50-60 characters</div>
                </div>

                <div class="mb-3">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea class="form-control"
                        name="meta_description"
                        id="meta_description"
                        rows="3"
                        placeholder="Brief description for search engines"><?= htmlspecialchars($post['meta_description'] ?? '') ?></textarea>
                    <div class="form-text">Recommended: 150-160 characters</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Publish Box -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-paper-plane"></i> Publish</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="draft" <?= ($post['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="private" <?= ($post['status'] ?? '') === 'private' ? 'selected' : '' ?>>Private</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $is_edit ? 'Update Post' : 'Save Post' ?>
                    </button>
                    <?php if ($is_edit && $post['status'] === 'published'): ?>
                        <a href="/post/<?= $post['slug'] ?>" class="btn btn-outline-secondary" target="_blank">
                            <i class="fas fa-eye"></i> Preview
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5><i class="fas fa-folder"></i> Categories</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($categories)): ?>
                    <div style="max-height: 200px; overflow-y: auto;">
                        <?php foreach ($categories as $category): ?>
                            <div class="form-check">
                                <input class="form-check-input"
                                    type="checkbox"
                                    name="categories[]"
                                    value="<?= $category['id'] ?>"
                                    id="cat_<?= $category['id'] ?>"
                                    <?= in_array($category['id'], $post_categories ?? []) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cat_<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No categories available.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tags -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-tags"></i> Tags</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <input type="text" 
                        class="form-control" 
                        name="tags" 
                        id="tags-input"
                        placeholder="Enter tags separated by commas..."
                        value="<?= htmlspecialchars(implode(', ', $post_tags ?? [])) ?>">
                    <div class="form-text">Separate tags with commas. New tags will be created automatically.</div>
                </div>

                <div id="tags-suggestions" class="mt-2" style="display: none;">
                    <small class="text-muted">Popular tags:</small>
                    <div id="popular-tags" class="mt-1"></div>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-image"></i> Featured Image</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="featured-image-preview mb-3">
                        <?php if (!empty($post['featured_image'])): ?>
                            <img src="<?= htmlspecialchars($post['featured_image']) ?>"
                                class="img-fluid rounded"
                                style="max-height: 200px;"
                                alt="Featured Image">
                        <?php else: ?>
                            <div class="border rounded p-4" style="min-height: 150px; background: #f8f9fa;">
                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                <p class="text-muted">No image selected</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <input type="file"
                        class="form-control mb-2"
                        name="featured_image"
                        accept="image/*"
                        onchange="previewImage(this)">

                    <?php if (!empty($post['featured_image'])): ?>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeFeaturedImage()">
                            <i class="fas fa-trash"></i> Remove Image
                        </button>
                        <input type="hidden" name="remove_featured_image" id="remove_featured_image" value="0">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- New Category Modal -->
<div class="modal fade" id="newCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="newCategoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Description</label>
                        <textarea class="form-control" id="category_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$inline_js = "
function generateSlug(title) {
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9 -]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
}

// Tags functionality
document.addEventListener('DOMContentLoaded', function() {
    const tagsInput = document.getElementById('tags-input');
    const tagsSuggestions = document.getElementById('tags-suggestions');
    const popularTags = document.getElementById('popular-tags');
    
    // Load popular tags
    loadPopularTags();
    
    // Show suggestions on focus
    tagsInput.addEventListener('focus', function() {
        tagsSuggestions.style.display = 'block';
    });
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!tagsInput.contains(e.target) && !tagsSuggestions.contains(e.target)) {
            tagsSuggestions.style.display = 'none';
        }
    });
    
    function loadPopularTags() {
        // This would typically be an AJAX call to get popular tags
        // For now, we'll show some example tags
        const exampleTags = ['Technology', 'Web Development', 'PHP', 'JavaScript', 'Tutorial', 'News'];
        
        popularTags.innerHTML = '';
        exampleTags.forEach(tag => {
            const tagBtn = document.createElement('button');
            tagBtn.type = 'button';
            tagBtn.className = 'btn btn-outline-secondary btn-sm me-1 mb-1';
            tagBtn.textContent = tag;
            tagBtn.onclick = function() {
                addTag(tag);
            };
            popularTags.appendChild(tagBtn);
        });
    }
    
    function addTag(tagName) {
        const currentTags = tagsInput.value.split(',').map(t => t.trim()).filter(t => t);
        if (!currentTags.includes(tagName)) {
            currentTags.push(tagName);
            tagsInput.value = currentTags.join(', ');
        }
    }
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.querySelector('.featured-image-preview');
            preview.innerHTML = '<img src=\"' + e.target.result + '\" class=\"img-fluid rounded\" style=\"max-height: 200px;\" alt=\"Featured Image Preview\">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removeFeaturedImage() {
    document.getElementById('remove_featured_image').value = '1';
    const preview = document.querySelector('.featured-image-preview');
    preview.innerHTML = '<div class=\"border rounded p-4\" style=\"min-height: 150px; background: #f8f9fa;\"><i class=\"fas fa-image fa-3x text-muted mb-2\"></i><p class=\"text-muted\">Image will be removed</p></div>';
}

// Handle new category form
document.getElementById('newCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('category_name').value;
    const description = document.getElementById('category_description').value;
    
    // Here you would typically make an AJAX call to create the category
    // For now, we'll just close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('newCategoryModal'));
    modal.hide();
    
    // Reset form
    this.reset();
    
    // Show success message
    alert('Category creation feature will be implemented with AJAX');
});
";
include dirname(__DIR__) . '/includes/footer.php';
?>