<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'CMS Admin') ?> - <?= htmlspecialchars(Config::get('app.name', 'My CMS')) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description ?? 'CMS Administration Panel') ?>">

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Admin Styles -->
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
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-bottom: 1px solid #34495e;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background: #34495e;
            color: white;
        }

        .sidebar .nav-link.active {
            background: #3498db;
            color: white;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card h3 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-radius: 10px;
        }

        .btn {
            border-radius: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
        }

        .page-header {
            background: white;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid #e9ecef;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    <!-- Additional CSS -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link href="<?= $css ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- TinyMCE for rich text editing -->
    <?php if (isset($include_editor) && $include_editor): ?>
        <script src="https://cdn.tiny.cloud/1/dol3snypfyo1gtym9ddhioylfmk572duxpml3ez86uy6pgrj/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <?php endif; ?>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <!-- Mobile menu toggle -->
        <button class="btn btn-primary d-md-none mb-3" type="button" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i> Menu
        </button>

        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1"><?= htmlspecialchars($page_title ?? 'Dashboard') ?></h1>
                    <?php if (isset($page_subtitle)): ?>
                        <p class="text-muted mb-0"><?= htmlspecialchars($page_subtitle) ?></p>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-3">Welcome, <?= htmlspecialchars(Auth::user()['name']) ?>!</span>
                    <a href="/admin/logout" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>