/**
 * WordPress-like Media Picker
 */
class MediaPicker {
    constructor(options = {}) {
        this.options = {
            multiple: false,
            type: 'image', // image, video, audio, document, all
            onSelect: null,
            ...options
        };
        
        this.selectedMedia = [];
        this.currentPage = 1;
        this.isLoading = false;
        
        this.createModal();
        this.bindEvents();
    }
    
    createModal() {
        const modalHtml = `
            <div class="modal fade" id="mediaPickerModal" tabindex="-1" data-bs-backdrop="static">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-images"></i> Select Media
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Toolbar -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-primary btn-sm" id="uploadNewBtn">
                                                <i class="fas fa-upload"></i> Upload Files
                                            </button>
                                            <div class="btn-group" role="group">
                                                <input type="radio" class="btn-check" name="viewMode" id="gridView" checked>
                                                <label class="btn btn-outline-secondary btn-sm" for="gridView">
                                                    <i class="fas fa-th"></i>
                                                </label>
                                                <input type="radio" class="btn-check" name="viewMode" id="listView">
                                                <label class="btn btn-outline-secondary btn-sm" for="listView">
                                                    <i class="fas fa-list"></i>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <select class="form-select form-select-sm" id="mediaTypeFilter" style="width: auto;">
                                                <option value="">All Types</option>
                                                <option value="image">Images</option>
                                                <option value="video">Videos</option>
                                                <option value="audio">Audio</option>
                                                <option value="application">Documents</option>
                                            </select>
                                            <input type="text" class="form-control form-control-sm" id="mediaSearch" placeholder="Search..." style="width: 200px;">
                                        </div>
                                    </div>
                                    
                                    <!-- Media Grid -->
                                    <div id="mediaGrid" class="media-grid">
                                        <div class="text-center py-5">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Pagination -->
                                    <div id="mediaPagination" class="d-flex justify-content-center mt-3"></div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="media-sidebar">
                                        <div id="mediaDetails" class="card">
                                            <div class="card-body text-center text-muted">
                                                <i class="fas fa-mouse-pointer fa-3x mb-3"></i>
                                                <p>Select a media file to view details</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Upload Area -->
                                        <div id="uploadArea" class="card mt-3" style="display: none;">
                                            <div class="card-body">
                                                <div class="upload-dropzone border-dashed border-2 border-primary rounded p-4 text-center">
                                                    <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                                    <p class="mb-2">Drag files here or click to browse</p>
                                                    <input type="file" id="mediaUploadInput" multiple style="display: none;">
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('mediaUploadInput').click()">
                                                        Browse Files
                                                    </button>
                                                </div>
                                                <div id="uploadProgress" class="mt-3" style="display: none;">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="me-auto">
                                <span id="selectedCount">0 selected</span>
                            </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="selectMediaBtn" disabled>
                                Select Media
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.modal = new bootstrap.Modal(document.getElementById('mediaPickerModal'));
    }
    
    bindEvents() {
        const modal = document.getElementById('mediaPickerModal');
        
        // Search
        modal.querySelector('#mediaSearch').addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.loadMedia(1, e.target.value);
            }, 500);
        });
        
        // Type filter
        modal.querySelector('#mediaTypeFilter').addEventListener('change', (e) => {
            this.loadMedia(1, modal.querySelector('#mediaSearch').value, e.target.value);
        });
        
        // Upload toggle
        modal.querySelector('#uploadNewBtn').addEventListener('click', () => {
            const uploadArea = modal.querySelector('#uploadArea');
            uploadArea.style.display = uploadArea.style.display === 'none' ? 'block' : 'none';
        });
        
        // File upload
        modal.querySelector('#mediaUploadInput').addEventListener('change', (e) => {
            this.uploadFiles(e.target.files);
        });
        
        // Select button
        modal.querySelector('#selectMediaBtn').addEventListener('click', () => {
            if (this.options.onSelect && this.selectedMedia.length > 0) {
                this.options.onSelect(this.options.multiple ? this.selectedMedia : this.selectedMedia[0]);
            }
            this.modal.hide();
        });
        
        // Modal shown event
        modal.addEventListener('shown.bs.modal', () => {
            this.loadMedia();
        });
        
        // Modal hidden event
        modal.addEventListener('hidden.bs.modal', () => {
            this.selectedMedia = [];
            this.updateSelectedCount();
        });
    }
    
    show() {
        this.modal.show();
    }
    
    loadMedia(page = 1, search = '', type = '') {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.currentPage = page;
        
        const params = new URLSearchParams({
            page: page,
            search: search,
            type: type
        });
        
        fetch(`/admin/media/library?${params}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.renderMediaGrid(data.media);
                    this.renderPagination(data.pagination);
                }
            })
            .catch(error => {
                console.error('Error loading media:', error);
            })
            .finally(() => {
                this.isLoading = false;
            });
    }
    
    renderMediaGrid(media) {
        const grid = document.getElementById('mediaGrid');
        
        if (media.length === 0) {
            grid.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-images fa-4x text-muted mb-3"></i>
                    <h5>No media found</h5>
                    <p class="text-muted">Try uploading some files or adjusting your search.</p>
                </div>
            `;
            return;
        }
        
        const gridHtml = media.map(item => {
            const isImage = item.mime_type.startsWith('image/');
            const isSelected = this.selectedMedia.some(selected => selected.id === item.id);
            
            return `
                <div class="media-item ${isSelected ? 'selected' : ''}" data-media-id="${item.id}">
                    <div class="media-thumbnail">
                        ${isImage ? 
                            `<img src="${item.file_path}" alt="${item.alt_text || item.original_filename}">` :
                            `<div class="file-icon"><i class="fas fa-file"></i></div>`
                        }
                        <div class="media-overlay">
                            <button type="button" class="btn btn-sm btn-primary" onclick="mediaPicker.selectMedia(${item.id})">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                    <div class="media-info">
                        <div class="media-title" title="${item.original_filename}">
                            ${item.original_filename.length > 20 ? item.original_filename.substring(0, 17) + '...' : item.original_filename}
                        </div>
                        <div class="media-meta">
                            ${this.formatFileSize(item.file_size)}
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        grid.innerHTML = `<div class="media-grid-container">${gridHtml}</div>`;
        
        // Add click handlers
        grid.querySelectorAll('.media-item').forEach(item => {
            item.addEventListener('click', (e) => {
                if (!e.target.closest('.media-overlay')) {
                    const mediaId = parseInt(item.dataset.mediaId);
                    this.showMediaDetails(mediaId);
                }
            });
        });
    }
    
    renderPagination(pagination) {
        const container = document.getElementById('mediaPagination');
        
        if (pagination.total_pages <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let paginationHtml = '<nav><ul class="pagination pagination-sm">';
        
        // Previous button
        if (pagination.current_page > 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="mediaPicker.loadMedia(${pagination.current_page - 1})">Previous</a></li>`;
        }
        
        // Page numbers
        for (let i = 1; i <= pagination.total_pages; i++) {
            if (i === pagination.current_page) {
                paginationHtml += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="mediaPicker.loadMedia(${i})">${i}</a></li>`;
            }
        }
        
        // Next button
        if (pagination.current_page < pagination.total_pages) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" onclick="mediaPicker.loadMedia(${pagination.current_page + 1})">Next</a></li>`;
        }
        
        paginationHtml += '</ul></nav>';
        container.innerHTML = paginationHtml;
    }
    
    selectMedia(mediaId) {
        const mediaItem = document.querySelector(`[data-media-id="${mediaId}"]`);
        const isSelected = mediaItem.classList.contains('selected');
        
        if (this.options.multiple) {
            if (isSelected) {
                // Deselect
                mediaItem.classList.remove('selected');
                this.selectedMedia = this.selectedMedia.filter(item => item.id !== mediaId);
            } else {
                // Select
                mediaItem.classList.add('selected');
                this.addToSelected(mediaId);
            }
        } else {
            // Single selection - clear others first
            document.querySelectorAll('.media-item.selected').forEach(item => {
                item.classList.remove('selected');
            });
            this.selectedMedia = [];
            
            if (!isSelected) {
                mediaItem.classList.add('selected');
                this.addToSelected(mediaId);
            }
        }
        
        this.updateSelectedCount();
    }
    
    addToSelected(mediaId) {
        // Get media details and add to selected array
        fetch(`/admin/media/details/${mediaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.selectedMedia.push(data.media);
                    this.updateSelectedCount();
                }
            });
    }
    
    showMediaDetails(mediaId) {
        fetch(`/admin/media/details/${mediaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.renderMediaDetails(data.media);
                }
            });
    }
    
    renderMediaDetails(media) {
        const detailsContainer = document.getElementById('mediaDetails');
        const isImage = media.mime_type.startsWith('image/');
        
        const detailsHtml = `
            <div class="card-body">
                <div class="text-center mb-3">
                    ${isImage ? 
                        `<img src="${media.file_path}" class="img-fluid rounded" style="max-height: 200px;" alt="${media.alt_text || media.original_filename}">` :
                        `<div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 150px;"><i class="fas fa-file fa-3x text-muted"></i></div>`
                    }
                </div>
                <h6 class="card-title">${media.original_filename}</h6>
                <table class="table table-sm">
                    <tr><td><strong>Type:</strong></td><td>${media.mime_type}</td></tr>
                    <tr><td><strong>Size:</strong></td><td>${this.formatFileSize(media.file_size)}</td></tr>
                    ${media.width && media.height ? `<tr><td><strong>Dimensions:</strong></td><td>${media.width} × ${media.height}</td></tr>` : ''}
                    <tr><td><strong>Uploaded:</strong></td><td>${new Date(media.created_at).toLocaleDateString()}</td></tr>
                </table>
                ${media.alt_text ? `<p><strong>Alt Text:</strong> ${media.alt_text}</p>` : ''}
                ${media.caption ? `<p><strong>Caption:</strong> ${media.caption}</p>` : ''}
                <button type="button" class="btn btn-primary btn-sm w-100" onclick="mediaPicker.selectMedia(${media.id})">
                    <i class="fas fa-check"></i> Select This File
                </button>
            </div>
        `;
        
        detailsContainer.innerHTML = detailsHtml;
    }
    
    updateSelectedCount() {
        const count = this.selectedMedia.length;
        const countElement = document.getElementById('selectedCount');
        const selectButton = document.getElementById('selectMediaBtn');
        
        countElement.textContent = `${count} selected`;
        selectButton.disabled = count === 0;
        selectButton.textContent = count > 1 ? `Select ${count} Files` : 'Select File';
    }
    
    uploadFiles(files) {
        const uploadProgress = document.getElementById('uploadProgress');
        uploadProgress.style.display = 'block';
        
        let completed = 0;
        const total = files.length;
        
        Array.from(files).forEach(file => {
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
                uploadProgress.querySelector('.progress-bar').style.width = progress + '%';
                
                if (completed === total) {
                    setTimeout(() => {
                        uploadProgress.style.display = 'none';
                        this.loadMedia(); // Refresh media grid
                    }, 1000);
                }
            });
        });
    }
    
    formatFileSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let unitIndex = 0;
        
        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }
        
        return Math.round(size * 100) / 100 + ' ' + units[unitIndex];
    }
}

// Global instance
let mediaPicker;

// Helper function to open media picker
function openMediaPicker(options = {}) {
    if (!mediaPicker) {
        mediaPicker = new MediaPicker(options);
    } else {
        mediaPicker.options = { ...mediaPicker.options, ...options };
    }
    mediaPicker.show();
}