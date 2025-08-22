<?php include APP_PATH . '/views/admin/includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include APP_PATH . '/views/admin/includes/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Plugin Settings</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="/admin/plugins" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Plugins
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><?= htmlspecialchars($plugin['name']) ?> Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Version:</strong> <?= htmlspecialchars($plugin['version'] ?? '1.0.0') ?><br>
                                <strong>Author:</strong> 
                                <?php if (isset($plugin['author']) && $plugin['author']): ?>
                                    <?php if (isset($plugin['author_uri']) && $plugin['author_uri']): ?>
                                        <a href="<?= htmlspecialchars($plugin['author_uri']) ?>" target="_blank"><?= htmlspecialchars($plugin['author']) ?></a>
                                    <?php else: ?>
                                        <?= htmlspecialchars($plugin['author']) ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Unknown</span>
                                <?php endif; ?>
                            </div>

                            <form method="POST">
                                <div class="mb-3">
                                    <label for="settings" class="form-label">Plugin Settings</label>
                                    <textarea class="form-control" id="settings" name="settings" rows="10" placeholder="Enter plugin settings in JSON format"><?= htmlspecialchars(json_encode($settings, JSON_PRETTY_PRINT)) ?></textarea>
                                    <div class="form-text">Enter settings in valid JSON format. This will be passed to your plugin.</div>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Plugin Information</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Name:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($plugin['name']) ?></dd>
                                
                                <dt class="col-sm-4">Description:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($plugin['description'] ?? 'No description available') ?></dd>
                                
                                <dt class="col-sm-4">File:</dt>
                                <dd class="col-sm-8"><code><?= htmlspecialchars($plugin['plugin_file']) ?></code></dd>
                                
                                <?php if (isset($plugin['uri']) && $plugin['uri']): ?>
                                    <dt class="col-sm-4">Plugin URI:</dt>
                                    <dd class="col-sm-8">
                                        <a href="<?= htmlspecialchars($plugin['uri']) ?>" target="_blank">Visit</a>
                                    </dd>
                                <?php endif; ?>
                            </dl>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Help</h6>
                        </div>
                        <div class="card-body">
                            <p class="small text-muted">
                                Plugin settings are stored as JSON and can be accessed by your plugin using the PluginManager.
                            </p>
                            <p class="small text-muted">
                                Example usage in your plugin:
                            </p>
                            <pre class="small"><code>$settings = $this->pluginManager->getPluginSettings('plugin_name');</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include APP_PATH . '/views/admin/includes/footer.php'; ?> 