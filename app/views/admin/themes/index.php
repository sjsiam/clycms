<?php
$page_title = 'Themes';
$page_subtitle = 'Manage installed themes';
$page_description = 'View and activate themes for your website';
include dirname(__DIR__) . '/includes/header.php';
?>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error_message) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-paint-brush"></i> Installed Themes</h5>
    </div>
    <div class="card-body">
        <?php if (empty($themes)): ?>
            <div class="text-center py-5">
                <i class="fas fa-paint-brush fa-4x text-muted mb-3"></i>
                <h4>No themes found</h4>
                <p class="text-muted">Please ensure theme folders are placed in the themes directory and contain an index.php file.</p>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($themes as $theme): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($theme) ?>
                                    <?php if ($theme === $active_theme): ?>
                                        <span class="badge bg-success ms-2">Active</span>
                                    <?php endif; ?>
                                </h5>
                                <p class="card-text">Theme: <?= htmlspecialchars($theme) ?></p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <form method="POST">
                                    <input type="hidden" name="theme" value="<?= htmlspecialchars($theme) ?>">
                                    <input type="hidden" name="action" value="activate">
                                    <button type="submit" class="btn btn-sm btn-primary" <?= $theme === $active_theme ? 'disabled' : '' ?>>
                                        <i class="fas fa-check"></i> Activate
                                    </button>
                                </form>
                                <?php if ($theme !== 'default'): ?>
                                    <form method="POST" onsubmit="return confirmDelete('Are you sure you want to delete the theme &quot;<?= htmlspecialchars($theme) ?>&quot;? This action cannot be undone.')">
                                        <input type="hidden" name="theme" value="<?= htmlspecialchars($theme) ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
include dirname(__DIR__) . '/includes/footer.php';
?>