<?php
$page_title = 'Edit Media';
$page_subtitle = 'Update media file details';
$page_description = 'Edit media file - ' . htmlspecialchars($media['original_filename']);
include dirname(__DIR__) . '/includes/header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-edit"></i> Edit Media Details</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="alt_text" class="form-label">Alt Text</label>
                        <input type="text" class="form-control" name="alt_text" id="alt_text" 
                               value="<?= htmlspecialchars($media['alt_text'] ?? '') ?>"
                               placeholder="Describe the image for accessibility">
                        <div class="form-text">Used for screen readers and when image fails to load.</div>
                    </div>

                    <div class="mb-3">
                        <label for="caption" class="form-label">Caption</label>
                        <textarea class="form-control" name="caption" id="caption" rows="3"
                                  placeholder="Image caption..."><?= htmlspecialchars($media['caption'] ?? '') ?></textarea>
                        <div class="form-text">Caption displayed with the image.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="4"
                                  placeholder="Detailed description..."><?= htmlspecialchars($media['description'] ?? '') ?></textarea>
                        <div class="form-text">Detailed description for organization purposes.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Media
                        </button>
                        <a href="/admin/media" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Library
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Media Information</h5>
            </div>
            <div class="card-body">
                <!-- Media Preview -->
                <div class="text-center mb-3">
                    <?php if (strpos($media['mime_type'], 'image/') === 0): ?>
                        <img src="<?= htmlspecialchars($media['file_path']) ?>" 
                             class="img-fluid rounded" 
                             alt="<?= htmlspecialchars($media['alt_text'] ?: $media['original_filename']) ?>"
                             style="max-height: 200px;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 200px;">
                            <i class="fas fa-file fa-4x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- File Details -->
                <table class="table table-sm">
                    <tr>
                        <td><strong>Filename:</strong></td>
                        <td><?= htmlspecialchars($media['original_filename']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>File Type:</strong></td>
                        <td><?= htmlspecialchars($media['mime_type']) ?></td>
                    </tr>
                    <tr>
                        <td><strong>File Size:</strong></td>
                        <td><?= (new Media())->formatFileSize($media['file_size']) ?></td>
                    </tr>
                    <?php if ($media['width'] && $media['height']): ?>
                    <tr>
                        <td><strong>Dimensions:</strong></td>
                        <td><?= $media['width'] ?> × <?= $media['height'] ?> pixels</td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><strong>Uploaded:</strong></td>
                        <td><?= date('M j, Y g:i A', strtotime($media['created_at'])) ?></td>
                    </tr>
                </table>

                <!-- File URL -->
                <div class="mb-3">
                    <label class="form-label">File URL:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" value="<?= Config::get('app.url') . $media['file_path'] ?>" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard(this.previousElementSibling.value)">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <a href="<?= htmlspecialchars($media['file_path']) ?>" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View File
                    </a>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteMedia(<?= $media['id'] ?>)">
                        <i class="fas fa-trash"></i> Delete File
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$inline_js = "
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('URL copied to clipboard!');
    });
}

function deleteMedia(mediaId) {
    if (confirm('Are you sure you want to delete this media file? This action cannot be undone.')) {
        window.location.href = '/admin/media/delete/' + mediaId;
    }
}
";
include dirname(__DIR__) . '/includes/footer.php';
?>