<?php
$page_title = 'Plugins';
$page_subtitle = 'Manage your plugs';
$page_description = 'Manage all your plugins';
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-file-alt"></i> Plugins</h5>
        <div class="btn-toolbar mb-2 mb-md-0">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Plugin</th>
                        <th>Description</th>
                        <th>Version</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($plugins)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <p class="my-4">No plugins found. Create a plugin in the <code>plugins/</code> directory.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($plugins as $plugin): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($plugin['name']) ?></strong>
                                    <?php if (isset($plugin['uri']) && $plugin['uri']): ?>
                                        <br><small><a href="<?= htmlspecialchars($plugin['uri']) ?>" target="_blank">Plugin URI</a></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($plugin['description'] ?? 'No description available') ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($plugin['version'] ?? '1.0.0') ?></span>
                                </td>
                                <td>
                                    <?php if (isset($plugin['author']) && $plugin['author']): ?>
                                        <?php if (isset($plugin['author_uri']) && $plugin['author_uri']): ?>
                                            <a href="<?= htmlspecialchars($plugin['author_uri']) ?>" target="_blank"><?= htmlspecialchars($plugin['author']) ?></a>
                                        <?php else: ?>
                                            <?= htmlspecialchars($plugin['author']) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Unknown</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($plugin['status'] === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif ($plugin['status'] === 'inactive'): ?>
                                        <span class="badge bg-warning">Inactive</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Not Installed</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <?php if ($plugin['status'] === 'active'): ?>
                                            <form method="POST" action="/admin/plugins/deactivate" style="display: inline;">
                                                <input type="hidden" name="plugin" value="<?= htmlspecialchars($plugin['plugin_name'] ?? $plugin['name']) ?>">
                                                <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Are you sure you want to deactivate this plugin?')">
                                                    <i class="fas fa-pause"></i> Deactivate
                                                </button>
                                            </form>
                                            <a href="/admin/plugins/settings/<?= htmlspecialchars($plugin['plugin_name']) ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-cog"></i> Settings
                                            </a>
                                        <?php elseif ($plugin['status'] === 'inactive'): ?>
                                            <form method="POST" action="/admin/plugins/activate" style="display: inline;">
                                                <input type="hidden" name="plugin" value="<?= htmlspecialchars($plugin['plugin_name']) ?>">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-play"></i> Activate
                                                </button>
                                            </form>
                                            <form method="POST" action="/admin/plugins/delete" style="display: inline;">
                                                <input type="hidden" name="plugin" value="<?= htmlspecialchars($plugin['plugin_name']) ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this plugin? This action cannot be undone.')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="/admin/plugins/install" style="display: inline;">
                                                <input type="hidden" name="plugin" value="<?= htmlspecialchars($plugin['plugin_name']) ?>">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-download"></i> Install
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <h5>Plugin Development</h5>
            <p>To create a new plugin:</p>
            <ol>
                <li>Create a new directory in the <code>plugins/</code> folder</li>
                <li>Create a PHP file with the same name as the directory</li>
                <li>Add plugin header information (Plugin Name, Description, Version, Author)</li>
                <li>Implement your plugin functionality using hooks and filters</li>
                <li>Refresh this page to see your plugin</li>
            </ol>

            <div class="alert alert-info">
                <h6>Available Hooks:</h6>
                <ul class="mb-0">
                    <li><code>init</code> - Called when the application initializes</li>
                    <li><code>admin_head</code> - Called in admin header</li>
                    <li><code>admin_footer</code> - Called in admin footer</li>
                    <li><code>clycms_head</code> - Called in public header</li>
                    <li><code>clycms_footer</code> - Called in public footer</li>
                    <li><code>content_filter</code> - Filter post content</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include APP_PATH . '/views/admin/includes/footer.php'; ?>