<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CMS Admin</title>
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

        .sidebar .nav-link.active {
            background: #3498db;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
        }

        .stats-card h3 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard</h1>
            <div class="d-flex align-items-center">
                <span class="me-3">Welcome, <?= htmlspecialchars($current_user['name']) ?>!</span>
                <a href="/admin/logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

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
                <div class="stats-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
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
                <div class="stats-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333;">
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
                <div class="stats-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
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

        <!-- Recent Posts -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Posts</h5>
                <a href="/admin/posts/create" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> New Post
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($recent_posts)): ?>
                    <p class="text-muted">No posts yet. <a href="/admin/posts/create">Create your first post!</a></p>
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
                                        </td>
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
                                            <a href="/post/<?= $post['slug'] ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
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