<?php
$page_title = 'Media Library';
$page_subtitle = 'Manage your media files';
$page_description = 'Upload and manage images, documents, and other media files';
include dirname(__DIR__) . '/includes/header.php';
?>

<!-- Success/Error Messages -->
<?php if (isset($_GET['uploaded'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> File uploaded successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> Media updated successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> Media deleted successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-images"></i> Media Library</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload"></i> Upload Files
        </button>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" class="form-control me-2" name="search" placeholder="Search media..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex justify-content-end">
                    <select name="type" class="form-select me-2" style="width: auto;" onchange="this.form.submit()">
                        <option value="">All Media Types</option>
                        <option value="image" <?= $type === 'image' ? 'selected' : '' ?>>Images</option>
                        <option value="video" <?= $type === 'video' ? 'selected' : '' ?>>Videos</option>
                        <option value="audio" <?= $type === 'audio' ? 'selected' : '' ?>>Audio</option>
                        <option value="application" <?= $type === 'application' ? 'selected' : '' ?>>Documents</option>
                    </select>
                </form>
            </div>
        </div>

        <?php if (empty($mediaFiles)): ?>
            <div class="text-center py-5">
                <i class="fas fa-images fa-5x text-muted mb-4"></i>
                <h4>No media files found</h4>
                <p class="text-muted mb-4">Upload your first media file to get started!</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload"></i> Upload Files
                </button>
            </div>
        <?php else: ?>
            <div class="row" id="media-grid">
                <?php foreach ($mediaFiles as $media): ?>
                    <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                        <div class="card media-item h-100" data-media-id="<?= $media['id'] ?>">
                            <div class="media-thumbnail">
                                <?php if (strpos($media['mime_type'], 'image/') === 0): ?>
                                    <img src="<?= htmlspecialchars($media['file_path']) ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($media['alt_text'] ?: $media['original_filename']) ?>"
                                         style="height: 150px; object-fit: cover; cursor: pointer;"
                                         onclick="openMediaDetails(<?= $media['id'] ?>)">
                                <?php else: ?>
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                                         style="height: 150px; cursor: pointer;"
                                         onclick="openMediaDetails(<?= $media['id'] ?>)">
                                        <i class="fas fa-file fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-2">
                                <h6 class="card-title small mb-1" title="<?= htmlspecialchars($media['original_filename']) ?>">
                                    <?= htmlspecialchars(strlen($media['original_filename']) > 20 ? substr($media['original_filename'], 0, 17) . '...' : $media['original_filename']) ?>
                                </h6>
                                <small class="text-muted">
                                    <?= (new Media())->formatFileSize($media['file_size']) ?>
                                </small>
                                <div class="mt-2">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openMediaDetails(<?= $media['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteMedia(<?= $media['id'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Media Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="upload-area" class="border-dashed border-2 border-primary rounded p-5 text-center mb-3">
                    <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                    <h5>Drag & Drop Files Here</h5>
                    <p class="text-muted">or click to browse files</p>
                    <input type="file" id="file-input" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.txt" style="display: none;">
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('file-input').click()">
                        <i class="fas fa-folder-open"></i> Browse Files
                    </button>
                </div>
                <div id="upload-progress" style="display: none;">
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div id="upload-status"></div>
                </div>
                <div id="upload-results"></div>
            </div>
        </div>
    </div>
</div>

<!-- Media Details Modal -->
<div class="modal fade" id="mediaDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Media Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="media-details-content">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<?php
$inline_js = "
// File upload functionality
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('file-input');
    const uploadProgress = document.getElementById('upload-progress');
    const uploadResults = document.getElementById('upload-results');

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('bg-light');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('bg-light');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('bg-light');
        const files = e.dataTransfer.files;
        uploadFiles(files);
    });

    // File input change
    fileInput.addEventListener('change', function(e) {
        uploadFiles(e.target.files);
    });

    function uploadFiles(files) {
        if (files.length === 0) return;

        uploadProgress.style.display = 'block';
        uploadResults.innerHTML = '';
        
        let completed = 0;
        const total = files.length;

        Array.from(files).forEach((file, index) => {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('ajax', '1');

            fetch('/admin/media/upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                completed++;
                const progress = (completed / total) * 100;
                document.querySelector('.progress-bar').style.width = progress + '%';
                
                const resultDiv = document.createElement('div');
                resultDiv.className = 'alert ' + (data.success ? 'alert-success' : 'alert-danger');
                resultDiv.innerHTML = '<strong>' + file.name + ':</strong> ' + data.message;
                uploadResults.appendChild(resultDiv);

                if (completed === total) {
                    setTimeout(() => {
                        location.reload(); // Refresh to show new files
                    }, 1000);
                }
            })
            .catch(error => {
                completed++;
                const resultDiv = document.createElement('div');
                resultDiv.className = 'alert alert-danger';
                resultDiv.innerHTML = '<strong>' + file.name + ':</strong> Upload failed';
                uploadResults.appendChild(resultDiv);
            });
        });
    }
});

function openMediaDetails(mediaId) {
    fetch('/admin/media/details/' + mediaId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const media = data.media;
                const isImage = media.mime_type.startsWith('image/');
                
                let content = '<div class=\"row\">';
                
                // Media preview
                content += '<div class=\"col-md-6\">';
                if (isImage) {
                    content += '<img src=\"' + media.file_path + '\" class=\"img-fluid rounded\" alt=\"' + (media.alt_text || media.original_filename) + '\">';
                } else {
                    content += '<div class=\"d-flex align-items-center justify-content-center bg-light rounded\" style=\"height: 200px;\">';
                    content += '<i class=\"fas fa-file fa-4x text-muted\"></i>';
                    content += '</div>';
                }
                content += '</div>';
                
                // Media info and edit form
                content += '<div class=\"col-md-6\">';
                content += '<form id=\"media-edit-form\" data-media-id=\"' + media.id + '\">';
                content += '<div class=\"mb-3\">';
                content += '<label class=\"form-label\">Filename</label>';
                content += '<input type=\"text\" class=\"form-control\" value=\"' + media.original_filename + '\" readonly>';
                content += '</div>';
                content += '<div class=\"mb-3\">';
                content += '<label class=\"form-label\">File Size</label>';
                content += '<input type=\"text\" class=\"form-control\" value=\"' + formatFileSize(media.file_size) + '\" readonly>';
                content += '</div>';
                if (isImage && media.width && media.height) {
                    content += '<div class=\"mb-3\">';
                    content += '<label class=\"form-label\">Dimensions</label>';
                    content += '<input type=\"text\" class=\"form-control\" value=\"' + media.width + ' × ' + media.height + ' pixels\" readonly>';
                    content += '</div>';
                }
                content += '<div class=\"mb-3\">';
                content += '<label class=\"form-label\">Alt Text</label>';
                content += '<input type=\"text\" class=\"form-control\" name=\"alt_text\" value=\"' + (media.alt_text || '') + '\">';
                content += '</div>';
                content += '<div class=\"mb-3\">';
                content += '<label class=\"form-label\">Caption</label>';
                content += '<textarea class=\"form-control\" name=\"caption\" rows=\"2\">' + (media.caption || '') + '</textarea>';
                content += '</div>';
                content += '<div class=\"mb-3\">';
                content += '<label class=\"form-label\">Description</label>';
                content += '<textarea class=\"form-control\" name=\"description\" rows=\"3\">' + (media.description || '') + '</textarea>';
                content += '</div>';
                content += '<div class=\"d-flex gap-2\">';
                content += '<button type=\"submit\" class=\"btn btn-primary\">Update</button>';
                content += '<button type=\"button\" class=\"btn btn-danger\" onclick=\"deleteMedia(' + media.id + ')\">Delete</button>';
                content += '</div>';
                content += '</form>';
                content += '</div>';
                content += '</div>';
                
                document.getElementById('media-details-content').innerHTML = content;
                
                // Handle form submission
                document.getElementById('media-edit-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    formData.append('ajax', '1');
                    
                    fetch('/admin/media/edit/' + media.id, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Media updated successfully!');
                            bootstrap.Modal.getInstance(document.getElementById('mediaDetailsModal')).hide();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    });
                });
                
                new bootstrap.Modal(document.getElementById('mediaDetailsModal')).show();
            }
        });
}

function deleteMedia(mediaId) {
    if (confirm('Are you sure you want to delete this media file? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('ajax', '1');
        
        fetch('/admin/media/delete/' + mediaId, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function formatFileSize(bytes) {
    const units = ['B', 'KB', 'MB', 'GB'];
    let size = bytes;
    let unitIndex = 0;
    
    while (size >= 1024 && unitIndex < units.length - 1) {
        size /= 1024;
        unitIndex++;
    }
    
    return Math.round(size * 100) / 100 + ' ' + units[unitIndex];
}
";

$additional_css = [
    'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css'
];

include dirname(__DIR__) . '/includes/footer.php';
?>