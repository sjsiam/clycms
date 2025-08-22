<?php
$page_title = 'Tag: ' . htmlspecialchars($tag['name']) . ' - ' . Config::get('app.name', 'My CMS Site');
$page_description = 'Posts tagged with ' . htmlspecialchars($tag['name']) . ($tag['description'] ? ' - ' . htmlspecialchars($tag['description']) : '');

// Additional styles for tag page
$additional_css = [];
$inline_css = "
        .tag-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 80px 0 50px;
        }
        .tag-meta {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }
";

include 'header.php';
?>

<style>
    <?= $inline_css ?>
</style>

<div class="tag-header">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-5 mb-4">
                    <i class="fas fa-tag me-3"></i>
                    <?= htmlspecialchars($tag['name']) ?>
                </h1>
                <?php if ($tag['description']): ?>
                    <p class="lead tag-meta"><?= htmlspecialchars($tag['description']) ?></p>
                <?php endif; ?>
                <div class="tag-meta">
                    <i class="fas fa-file-alt me-1"></i>
                    <?= count($posts) ?> post<?= count($posts) !== 1 ? 's' : '' ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <?php if (empty($posts)): ?>
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <i class="fas fa-file-alt fa-5x text-muted mb-4"></i>
                <h3>No Posts Found</h3>
                <p class="text-muted mb-4">There are no published posts with this tag yet.</p>
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
                        <?php if ($post['featured_image']): ?>
                            <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($post['title']) ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        
                        <div class="card-body">
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

        <!-- Back to Home -->
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="/" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Home
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>