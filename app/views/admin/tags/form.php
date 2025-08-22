<?php
$is_edit = isset($tag) && $tag;
$page_title = $is_edit ? 'Edit Tag' : 'Create New Tag';
$page_subtitle = $is_edit ? 'Update existing tag' : 'Add a new content tag';
$page_description = $is_edit ? 'Edit tag - ' . htmlspecialchars($tag['name']) : 'Create a new content tag';
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

<form method="POST" class="row">
    <div class="col-md-8">
        <!-- Tag Details -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Tag Name</label>
                    <input type="text"
                        class="form-control form-control-lg"
                        name="name"
                        id="name"
                        placeholder="Enter tag name..."
                        value="<?= htmlspecialchars($tag['name'] ?? '') ?>"
                        required
                        <?= !$is_edit ? 'oninput="generateSlug(this.value)"' : '' ?>>
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug (URL)</label>
                    <div class="input-group">
                        <span class="input-group-text"><?= Config::get('app.url') ?>/tag/</span>
                        <input type="text"
                            class="form-control"
                            name="slug"
                            id="slug"
                            value="<?= htmlspecialchars($tag['slug'] ?? '') ?>"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control"
                        name="description"
                        id="description"
                        rows="3"
                        placeholder="Brief description of the tag..."><?= htmlspecialchars($tag['description'] ?? '') ?></textarea>
                    <div class="form-text">Optional. Describe what this tag represents.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Save Box -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-save"></i> Save Tag</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $is_edit ? 'Update Tag' : 'Save Tag' ?>
                    </button>
                    <a href="/admin/tags" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Tags
                    </a>
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
";
include dirname(__DIR__) . '/includes/footer.php';
?>