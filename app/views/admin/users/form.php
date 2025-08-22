<?php
$is_edit = isset($user) && $user;
$page_title = $is_edit ? 'Edit User' : 'Create New User';
$page_subtitle = $is_edit ? 'Update existing user details' : 'Add a new user to the system';
$page_description = $is_edit ? 'Edit user - ' . htmlspecialchars($user['name']) : 'Create a new website user';
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

<form method="POST" class="row">
    <div class="col-md-8">
        <!-- User Details -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text"
                        class="form-control form-control-lg"
                        name="name"
                        id="name"
                        placeholder="Enter user name..."
                        value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email"
                        class="form-control"
                        name="email"
                        id="email"
                        placeholder="Enter user email..."
                        value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                        required>
                    <div class="form-text">Must be a unique email address.</div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                        class="form-control"
                        name="password"
                        id="password"
                        placeholder="<?= $is_edit ? 'Enter new password (leave blank to keep current)' : 'Enter password...' ?>"
                        <?= $is_edit ? '' : 'required' ?>>
                    <div class="form-text"><?= $is_edit ? 'Leave blank to keep the current password.' : 'Minimum 8 characters.' ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Publish Box -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-user-cog"></i> User Settings</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" name="role" id="role" <?= $is_edit && $user['id'] == Auth::id() ? 'disabled' : '' ?>>
                        <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="editor" <?= ($user['role'] ?? 'editor') === 'editor' ? 'selected' : '' ?>>Editor</option>
                        <option value="user" <?= ($user['role'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                    </select>
                    <?php if ($is_edit && $user['id'] == Auth::id()): ?>
                        <div class="form-text">You cannot change your own role.</div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= ($user['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?= $is_edit ? 'Update User' : 'Save User' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php
$inline_js = "
document.getElementById('email').addEventListener('input', function() {
    this.value = this.value.toLowerCase();
});
";
include dirname(__DIR__) . '/includes/footer.php';
?>