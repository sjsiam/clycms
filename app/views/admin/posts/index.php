<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - CMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            height: 100vh;
            background: #2c3e50;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            overflow-y: auto;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-bottom: 1px solid #34495e;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            color: white;
        }
    </style>
</head>
<body>
    <?php include dirname(__DIR__) . '/includes/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Posts</h1>
            <a href="/admin/posts/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Post
            </a>
        </div>
        
        <div class="card">
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
                                    <td>Author #<?= $post['author_id'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($post['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
                                    <td>
                                        <a href="/admin/posts/edit/<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($post['status'] === 'published'): ?>
                                            <a href="/post/<?= $post['slug'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
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
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>