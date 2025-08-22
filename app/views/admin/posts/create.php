<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - CMS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/dol3snypfyo1gtym9ddhioylfmk572duxpml3ez86uy6pgrj/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
            <h1>Create New Post</h1>
            <a href="/admin/posts" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Posts
            </a>
        </div>

        <form method="POST" class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <input type="text" class="form-control form-control-lg" name="title" placeholder="Enter post title..." required>
                        </div>

                        <div class="mb-3">
                            <textarea name="content" id="content-editor"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control" name="excerpt" id="excerpt" rows="3" placeholder="Brief description of the post..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-search"></i> SEO Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="SEO title for search engines">
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" name="meta_description" id="meta_description" rows="3" placeholder="Brief description for search engines (155 characters max)"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Publish Box -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-paper-plane"></i> Publish</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status" id="status">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Post
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-folder"></i> Categories</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" value="<?= $category['id'] ?>" id="cat_<?= $category['id'] ?>">
                                    <label class="form-check-label" for="cat_<?= $category['id'] ?>">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No categories available.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-image"></i> Featured Image</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="featured-image-placeholder border rounded p-4 mb-3" style="min-height: 150px; background: #f8f9fa;">
                                <i class="fas fa-image fa-3x text-muted mb-2"></i>
                                <p class="text-muted">No image selected</p>
                            </div>
                            <button type="button" class="btn btn-outline-primary">
                                <i class="fas fa-upload"></i> Set Featured Image
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content-editor',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 400,
            branding: false,
            menubar: false
        });
    </script>
</body>

</html>