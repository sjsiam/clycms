<?php
$page_title = 'Posts';
$page_subtitle = 'Manage your blog posts and articles';
$page_description = 'Manage all your blog posts and articles';
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-alt"></i> All Posts</h5>
        <a href="/admin/posts/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Post
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($posts)): ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h4>No posts yet</h4>
                <p class="text-muted">Get started by creating your first post!</p>
                <a href="/admin/posts/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create First Post
                </a>
            </div>
        <?php else: ?>
            <!-- Filter and Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
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
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($post['title']) ?></strong>
                                    <?php if ($post['excerpt']): ?>
                                        <br><small class="text-muted"><?= substr(htmlspecialchars($post['excerpt']), 0, 100) ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($post['author_name'] ?? 'Author #' . $post['author_id']) ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $post['status'] === 'published' ? 'success' : ($post['status'] === 'draft' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst($post['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($post['created_at'])) ?>
                                    <?php if ($post['updated_at'] !== $post['created_at']): ?>
                                        <br><small class="text-muted">Updated: <?= date('M j, Y', strtotime($post['updated_at'])) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($post['status'] === 'published'): ?>
                                            <a href="/post/<?= $post['slug'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePost(<?= $post['id'] ?>)" title="Delete">
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
function deletePost(id) {
    if (confirmDelete('Are you sure you want to delete this post? This action cannot be undone.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/posts/delete/' + id;

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