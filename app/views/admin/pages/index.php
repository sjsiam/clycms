<?php
$page_title = 'Pages';
$page_subtitle = 'Manage your website pages';
$page_description = 'Manage all your website pages';
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-alt"></i> All Pages</h5>
        <a href="/admin/pages/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Page
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($pages)): ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h4>No pages yet</h4>
                <p class="text-muted">Get started by creating your first page!</p>
                <a href="/admin/pages/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create First Page
                </a>
            </div>
        <?php else: ?>
            <!-- Filter and Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" name="search" placeholder="Search pages..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="d-flex justify-content-end">
                        <select name="status" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="published" <?= ($_GET['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
                            <option value="draft" <?= ($_GET['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                            <option value="private" <?= ($_GET['status'] ?? '') === 'private' ? 'selected' : '' ?>>Private</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($page['title']) ?></strong>
                                    <?php if ($page['excerpt']): ?>
                                        <br><small class="text-muted"><?= substr(htmlspecialchars($page['excerpt']), 0, 100) ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($page['author_name'] ?? 'Author #' . $page['author_id']) ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $page['status'] === 'published' ? 'success' : ($page['status'] === 'draft' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst($page['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($page['created_at'])) ?>
                                    <?php if ($page['updated_at'] !== $page['created_at']): ?>
                                        <br><small class="text-muted">Updated: <?= date('M j, Y', strtotime($page['updated_at'])) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin/pages/edit/<?= $page['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($page['status'] === 'published'): ?>
                                            <a href="/<?= $page['slug'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePage(<?= $page['id'] ?>)" title="Delete">
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
function deletePage(id) {
    if (confirmDelete('Are you sure you want to delete this page? This action cannot be undone.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/pages/delete/' + id;

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