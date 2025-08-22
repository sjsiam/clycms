<?php
$page_title = $query ? htmlspecialchars($query) . ' - Search - ' . Setting::get('site_title', 'My CMS Site') : 'Search - ' . Setting::get('site_title', 'My CMS Site');
$page_description = htmlspecialchars(Setting::get('site_description', 'A powerful PHP CMS built with MVC architecture'));

$inline_css = "
        .search-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0 40px;
        }
        .search-meta {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }
        .post-card {
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .post-card .card-title a {
            color: #333;
            text-decoration: none;
        }
        .post-card .card-title a:hover {
            color: #007bff;
        }
        .post-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .post-excerpt {
            color: #555;
            line-height: 1.6;
        }
";

include 'header.php';
?>

<style>
    <?= $inline_css ?>
</style>

<div class="search-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-5 mb-4">Search Results for: "<?php echo htmlspecialchars($query); ?>"</h1>
                <div class="search-meta">
                    <i class="fas fa-search me-1"></i>
                    Found <?php echo count($posts); ?> result(s)
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <?php if (empty($posts)): ?>
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <i class="fas fa-search fa-5x text-muted mb-4"></i>
                <h3>No Results Found</h3>
                <p class="text-muted mb-4">No posts match "<?php echo htmlspecialchars($query); ?>". Try a different search term.</p>
                <a href="/" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Home
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($posts as $post): ?>
                <div class="col-lg-6">
                    <div class="card post-card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php echo htmlspecialchars($post['url']); ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($post['title']); ?>
                                </a>
                            </h5>
                            <div class="post-meta mb-3">
                                <i class="fas fa-user me-1"></i>
                                By <?php echo htmlspecialchars($post['author_name']); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('M j, Y', strtotime($post['created_at'])); ?>
                            </div>
                            <?php if ($post['excerpt']): ?>
                                <p class="post-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                            <?php else: ?>
                                <p class="post-excerpt"><?php echo htmlspecialchars(substr(strip_tags($post['content']), 0, 150) . '...'); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo htmlspecialchars($post['url']); ?>" class="btn btn-outline-primary">
                                Read More <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>