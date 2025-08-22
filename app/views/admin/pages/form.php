<?php
$is_edit = isset($page) && $page;
$page_title = $is_edit ? 'Edit Page' : 'Create New Page';
$page_subtitle = $is_edit ? 'Update your existing page' : 'Write and publish a new page';
$page_description = $is_edit ? 'Edit page - ' . htmlspecialchars($page['title']) : 'Create a new website page';
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
                        placeholder="Enter page title..."
                        value="<?= htmlspecialchars($page['title'] ?? '') ?>"
                        required
                        <?= !$is_edit ? 'oninput="generateSlug(this.value)"' : '' ?>>
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug (URL)</label>
                    <div class="input-group">
                        <span class="input-group-text"><?= Config::get('app.url') ?>/page/</span>
                        <input type="text"
                            class="form-control"
                            name="slug"
                            id="slug"
                            value="<?= htmlspecialchars($page['slug'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="content" id="content" class="rich-editor"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea class="form-control"
                        name="excerpt"
                        id="excerpt"
                        rows="3"
                        placeholder="Brief description of the page..."><?= htmlspecialchars($page['excerpt'] ?? '') ?></textarea>
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
                        value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>"
                        placeholder="SEO title for search engines">
                    <div class="form-text">Recommended: 50-60 characters</div>
                </div>

                <div class="mb-3">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea class="form-control"
                        name="meta_description"
                        id="meta_description"
                        rows="3"
                        placeholder="Brief description for search engines"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
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
                        <option value="draft" <?= ($page['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= ($page['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="private" <?= ($page['status'] ?? '') === 'private' ? 'selected' : '' ?>>Private</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $is_edit ? 'Update Page' : 'Save Page' ?>
                    </button>
                    <?php if ($is_edit && $page['status'] === 'published'): ?>
                        <a href="/page/<?= $page['slug'] ?>" class="btn btn-outline-secondary" target="_blank">
                            <i class="fas fa-eye"></i> Preview
                        </a>
                    <?php endif; ?>
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
                        <?php if (!empty($page['featured_image'])): ?>
                            <img src="<?= htmlspecialchars($page['featured_image']) ?>"
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

                    <?php if (!empty($page['featured_image'])): ?>
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
";
include dirname(__DIR__) . '/includes/footer.php';
?>