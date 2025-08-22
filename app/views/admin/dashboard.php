<?php
$page_title = 'Dashboard';
$page_subtitle = 'Welcome to your CMS administration panel';
$page_description = 'CMS Dashboard - Manage your content, users, and settings';
include 'includes/header.php';
?>

<!-- Stats Cards -->
<div class="row mb-5">
    <div class="col-md-3">
        <div class="stats-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><?= $stats['total_posts'] ?></h3>
                    <p class="mb-0">Total Posts</p>
                </div>
                <i class="fas fa-file-alt fa-3x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><?= $stats['published_posts'] ?></h3>
                    <p class="mb-0">Published</p>
                </div>
                <i class="fas fa-check-circle fa-3x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: #333;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><?= $stats['draft_posts'] ?></h3>
                    <p class="mb-0">Drafts</p>
                </div>
                <i class="fas fa-edit fa-3x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card" style="background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><?= $stats['total_users'] ?></h3>
                    <p class="mb-0">Users</p>
                </div>
                <i class="fas fa-users fa-3x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="/admin/posts/create" class="btn btn-primary w-100">
                            <i class="fas fa-plus"></i> New Post
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/admin/pages/create" class="btn btn-success w-100">
                            <i class="fas fa-file"></i> New Page
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/admin/media" class="btn btn-info w-100">
                            <i class="fas fa-upload"></i> Upload Media
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="/admin/users/create" class="btn btn-warning w-100">
                            <i class="fas fa-user-plus"></i> Add User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Posts -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Posts</h5>
        <a href="/admin/posts" class="btn btn-outline-primary btn-sm">
            View All Posts
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($recent_posts)): ?>
            <div class="text-center py-4">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <h5>No posts yet</h5>
                <p class="text-muted">Get started by creating your first post!</p>
                <a href="/admin/posts/create" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create First Post
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_posts as $post): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($post['title']) ?></strong>
                                    <?php if ($post['excerpt']): ?>
                                        <br><small class="text-muted"><?= substr(htmlspecialchars($post['excerpt']), 0, 80) ?>...</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $post['status'] === 'published' ? 'success' : ($post['status'] === 'draft' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst($post['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
                                <td>
                                    <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($post['status'] === 'published'): ?>
                                        <a href="/post/<?= $post['slug'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>