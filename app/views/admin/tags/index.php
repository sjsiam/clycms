<?php
$page_title = 'Tags';
$page_subtitle = 'Manage your content tags';
$page_description = 'Manage all your content tags';
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-tags"></i> All Tags</h5>
        <a href="/admin/tags/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Tag
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($tags)): ?>
            <div class="text-center py-5">
                <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                <h4>No tags yet</h4>
                <p class="text-muted">Get started by creating your first tag!</p>
                <a href="/admin/tags/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create First Tag
                </a>
            </div>
        <?php else: ?>
            <!-- Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" name="search" placeholder="Search tags..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Posts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tags as $tag): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($tag['name']) ?></strong>
                                </td>
                                <td>
                                    <code><?= htmlspecialchars($tag['slug']) ?></code>
                                </td>
                                <td>
                                    <?= htmlspecialchars($tag['description'] ?: 'No description') ?>
                                </td>
                                <td>
                                    <span class="badge bg-primary"><?= $tag['post_count'] ?></span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin/tags/edit/<?= $tag['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/tag/<?= $tag['slug'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteTag(<?= $tag['id'] ?>)" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$inline_js = "
function deleteTag(id) {
    if (confirmDelete('Are you sure you want to delete this tag? This action cannot be undone.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/tags/delete/' + id;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_method';
        csrfInput.value = 'DELETE';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}
";
include dirname(__DIR__) . '/includes/footer.php';
?>