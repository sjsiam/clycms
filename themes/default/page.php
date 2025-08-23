<?php
$post_title = htmlspecialchars($post['meta_title'] ?: $post['title']) . ' - ' . Setting::get('site_title', 'ClyCMS');
$post_description = htmlspecialchars($post['meta_description'] ?: $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160));

// Additional styles for single page
$additional_css = [];
$inline_css = "
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0 50px;
        }
        .page-meta {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
        }
        .page-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .page-content h1, .page-content h2, .page-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .page-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .page-content blockquote {
            border-left: 4px solid #667eea;
            padding-left: 1rem;
            margin: 2rem 0;
            font-style: italic;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0 8px 8px 0;
        }
        .share-buttons .btn {
            margin: 0 5px;
        }
";

include 'header.php';
?>

<style>
    <?= $inline_css ?>
</style>

<div class="page-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php if ($post['featured_image']): ?>
                    <div class="mb-4">
                        <img src="<?= htmlspecialchars($post['featured_image']) ?>"
                            class="img-fluid rounded"
                            alt="<?= htmlspecialchars($post['title']) ?>"
                            style="max-height: 300px; width: 100%; object-fit: cover;">
                    </div>
                <?php endif; ?>

                <h1 class="display-5 mb-4"><?= htmlspecialchars($post['title']) ?></h1>
                <div class="page-meta">
                    <i class="fas fa-user me-1"></i>
                    By <?= htmlspecialchars($post['author_name']) ?>
                    <span class="mx-3">•</span>
                    <i class="fas fa-calendar me-1"></i>
                    <?= date('F j, Y', strtotime($post['created_at'])) ?>
                    <?php if ($post['updated_at'] !== $post['created_at']): ?>
                        <span class="mx-3">•</span>
                        <i class="fas fa-edit me-1"></i>
                        Updated <?= date('F j, Y', strtotime($post['updated_at'])) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <article class="page-content">
                <?= $post['content'] ?>
            </article>

            <hr class="my-5">

            <!-- Share and Navigation -->
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <a href="/" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Home
                </a>

                <div class="share-buttons">
                    <button class="btn btn-outline-secondary" onclick="sharePage('facebook')">
                        <i class="fab fa-facebook"></i>
                    </button>
                    <button class="btn btn-outline-secondary" onclick="sharePage('twitter')">
                        <i class="fab fa-twitter"></i>
                    </button>
                    <button class="btn btn-outline-secondary" onclick="sharePage('linkedin')">
                        <i class="fab fa-linkedin"></i>
                    </button>
                    <button class="btn btn-outline-secondary me-2" onclick="window.print()">
                        <i class="fas fa-print"></i>
                    </button>
                    <button class="btn btn-outline-secondary" onclick="sharePage('copy')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$inline_js = "
        function sharePage(platform) {
            const url = encodeURIComponent(window.location.href);
            const title = encodeURIComponent('" . addslashes($post['title']) . "');
            const text = encodeURIComponent('" . addslashes($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 100)) . "');

            let shareUrl = '';

            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=\${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?url=\${url}&text=\${title}`;
                    break;
                case 'linkedin':
                    shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=\${url}`;
                    break;
                case 'copy':
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(window.location.href).then(() => {
                            alert('Link copied to clipboard!');
                        });
                    } else {
                        // Fallback for older browsers
                        const textArea = document.createElement('textarea');
                        textArea.value = window.location.href;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        alert('Link copied to clipboard!');
                    }
                    return;
                default:
                    if (navigator.share) {
                        navigator.share({
                            title: '" . addslashes($post['title']) . "',
                            url: window.location.href
                        });
                        return;
                    }
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        }
";

include 'footer.php';
?>