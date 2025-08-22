<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Dynamic Title and SEO -->
    <title><?= htmlspecialchars($page_title ?? Config::get('app.name', 'My CMS Site')) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description ?? Config::get('app.description', 'A powerful PHP CMS')) ?>">

    <!-- SEO Meta Tags -->
    <?php if (isset($post) && $post): ?>
        <meta property="og:title" content="<?= htmlspecialchars($post['meta_title'] ?: $post['title']) ?>">
        <meta property="og:description" content="<?= htmlspecialchars($post['meta_description'] ?: $post['excerpt']) ?>">
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?= Config::get('app.url') ?>/post/<?= $post['slug'] ?>">
        <?php if ($post['featured_image']): ?>
            <meta property="og:image" content="<?= Config::get('app.url') . $post['featured_image'] ?>">
        <?php endif; ?>

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?= htmlspecialchars($post['meta_title'] ?: $post['title']) ?>">
        <meta name="twitter:description" content="<?= htmlspecialchars($post['meta_description'] ?: $post['excerpt']) ?>">
    <?php endif; ?>

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= Config::get('app.url') . $_SERVER['REQUEST_URI'] ?>">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Theme Styles -->
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #3498db;
            --text-color: #333;
            --light-bg: #f8f9fa;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 100px 0;
            margin-bottom: 50px;
        }

        .post-card {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            border-radius: 15px;
            overflow: hidden;
        }

        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .post-meta {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: var(--accent-color) !important;
        }

        .post-excerpt {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.7;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        footer {
            background: #2c3e50 !important;
        }

        .search-form .form-control {
            border-radius: 25px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .search-form .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .search-form .btn {
            border-radius: 25px;
        }
    </style>

    <?php
    // Plugin hooks - clycms_head
    if (isset($app) && method_exists($app, 'getPluginManager')) {

        $pluginManager = $app->getPluginManager();

        $pluginManager->doHook('clycms_head');
    }
    ?>


    <!-- Additional CSS from theme -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link href="<?= $css ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- JSON-LD Structured Data -->
    <?php if (isset($post) && $post): ?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "Article",
                "headline": "<?= htmlspecialchars($post['title']) ?>",
                "description": "<?= htmlspecialchars($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160)) ?>",
                "author": {
                    "@type": "Person",
                    "name": "<?= htmlspecialchars($post['author_name']) ?>"
                },
                "datePublished": "<?= date('c', strtotime($post['created_at'])) ?>",
                "dateModified": "<?= date('c', strtotime($post['updated_at'])) ?>",
                "publisher": {
                    "@type": "Organization",
                    "name": "<?= htmlspecialchars(Config::get('app.name', 'My CMS Site')) ?>"
                }
                <?php if ($post['featured_image']): ?>,
                    "image": "<?= Config::get('app.url') . $post['featured_image'] ?>"
                <?php endif; ?>
            }
        </script>
    <?php endif; ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-rocket me-2"></i>
                <?= htmlspecialchars(Config::get('app.name', 'My CMS Site')) ?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Admin</a>
                    </li>
                    <?php
                    if (isset($navigations)) {
                        foreach ($navigations as $nav) {
                            echo '<li class="nav-item"><a class="nav-link" href="/' . $nav['slug'] . '">' . $nav['title'] . '</a></li>';
                        }
                    }
                    ?>
                </ul>

                <form class="d-flex ms-3 search-form" action="/search" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search..." style="width: 200px;" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>