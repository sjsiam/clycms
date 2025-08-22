<?php
$page_title = Config::get('app.name', 'My CMS Site');
$page_description = Config::get('app.description', 'A powerful PHP CMS built with MVC architecture');
include 'header.php';
?>

<div class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 mb-4">Welcome to <?= htmlspecialchars(Config::get('app.name', 'My CMS')) ?></h1>
                <p class="lead mb-4"><?= htmlspecialchars(Config::get('app.description', 'A powerful, modern content management system built with PHP')) ?></p>
                <a href="/admin" class="btn btn-light btn-lg">
                    <i class="fas fa-cog me-2"></i>
                    Get Started
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <?php if (empty($posts)): ?>
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <i class="fas fa-file-alt fa-5x text-muted mb-4"></i>
                <h3>No Posts Yet</h3>
                <p class="text-muted mb-4">This site doesn't have any published posts yet. Check back soon!</p>
                <a href="/admin" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Create First Post
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
                <div class="col-lg-6">
                    <div class="card post-card h-100">
                        <div class="card-body">
                            <?php if ($post['featured_image']): ?>
                                <a href="/post/<?= $post['slug'] ?>">
                                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" class="card-img-top mb-3" alt="<?= htmlspecialchars($post['title']) ?>">
                                </a>
                            <?php endif; ?>
                            <h5 class="card-title">
                                <a href="/post/<?= $post['slug'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h5>

                            <div class="post-meta mb-3">
                                <i class="fas fa-user me-1"></i>
                                By <?= htmlspecialchars($post['author_name']) ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar me-1"></i>
                                <?= date('M j, Y', strtotime($post['created_at'])) ?>
                            </div>

                            <?php if ($post['excerpt']): ?>
                                <p class="post-excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
                            <?php else: ?>
                                <p class="post-excerpt"><?= substr(strip_tags($post['content']), 0, 150) ?>...</p>
                            <?php endif; ?>

                            <a href="/post/<?= $post['slug'] ?>" class="btn btn-outline-primary">
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