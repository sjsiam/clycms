<?php
$page_title = 'Settings';
$page_subtitle = 'Manage website settings';
$page_description = 'Configure general settings for your website';
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
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-cog"></i> General Settings</h5>
    </div>
    <div class="card-body">
        <form method="POST" class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="site_title" class="form-label">Site Title</label>
                    <input type="text"
                        class="form-control"
                        name="site_title"
                        id="site_title"
                        placeholder="Enter site title..."
                        value="<?= htmlspecialchars($settings['site_title'] ?? '') ?>"
                        required>
                    <div class="form-text">The name of your website.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="site_description" class="form-label">Site Description</label>
                    <textarea class="form-control"
                        name="site_description"
                        id="site_description"
                        rows="3"
                        placeholder="Enter site description..."><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
                    <div class="form-text">A brief description of your website.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="posts_per_page" class="form-label">Posts Per Page</label>
                    <input type="number"
                        class="form-control"
                        name="posts_per_page"
                        id="posts_per_page"
                        placeholder="Enter number of posts per page..."
                        value="<?= htmlspecialchars($settings['posts_per_page'] ?? '10') ?>"
                        min="1"
                        required>
                    <div class="form-text">Number of posts to display per page.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="date_format" class="form-label">Date Format</label>
                    <select class="form-select" name="date_format" id="date_format" required>
                        <option value="F j, Y" <?= ($settings['date_format'] ?? '') === 'F j, Y' ? 'selected' : '' ?>>F j, Y (e.g., <?= date('F j, Y') ?>)</option>
                        <option value="Y-m-d" <?= ($settings['date_format'] ?? '') === 'Y-m-d' ? 'selected' : '' ?>>Y-m-d (e.g., <?= date('Y-m-d') ?>)</option>
                        <option value="m/d/Y" <?= ($settings['date_format'] ?? '') === 'm/d/Y' ? 'selected' : '' ?>>m/d/Y (e.g., <?= date('m/d/Y') ?>)</option>
                        <option value="d/m/Y" <?= ($settings['date_format'] ?? '') === 'd/m/Y' ? 'selected' : '' ?>>d/m/Y (e.g., <?= date('d/m/Y') ?>)</option>
                    </select>
                    <div class="form-text">Choose how dates are displayed.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="time_format" class="form-label">Time Format</label>
                    <select class="form-select" name="time_format" id="time_format" required>
                        <option value="g:i a" <?= ($settings['time_format'] ?? '') === 'g:i a' ? 'selected' : '' ?>>g:i a (e.g., <?= date('g:i a') ?>)</option>
                        <option value="H:i" <?= ($settings['time_format'] ?? '') === 'H:i' ? 'selected' : '' ?>>H:i (e.g., <?= date('H:i') ?>)</option>
                    </select>
                    <div class="form-text">Choose how times are displayed.</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="start_of_week" class="form-label">Start of Week</label>
                    <select class="form-select" name="start_of_week" id="start_of_week" required>
                        <option value="0" <?= ($settings['start_of_week'] ?? '') === '0' ? 'selected' : '' ?>>Sunday</option>
                        <option value="1" <?= ($settings['start_of_week'] ?? '') === '1' ? 'selected' : '' ?>>Monday</option>
                        <option value="2" <?= ($settings['start_of_week'] ?? '') === '2' ? 'selected' : '' ?>>Tuesday</option>
                        <option value="3" <?= ($settings['start_of_week'] ?? '') === '3' ? 'selected' : '' ?>>Wednesday</option>
                        <option value="4" <?= ($settings['start_of_week'] ?? '') === '4' ? 'selected' : '' ?>>Thursday</option>
                        <option value="5" <?= ($settings['start_of_week'] ?? '') === '5' ? 'selected' : '' ?>>Friday</option>
                        <option value="6" <?= ($settings['start_of_week'] ?? '') === '6' ? 'selected' : '' ?>>Saturday</option>
                    </select>
                    <div class="form-text">Choose the first day of the week.</div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$inline_js = "
document.getElementById('posts_per_page').addEventListener('input', function() {
    if (this.value < 1) {
        this.value = 1;
    }
});
";
include dirname(__DIR__) . '/includes/footer.php';
?>