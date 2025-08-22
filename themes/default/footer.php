<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5>
                    <i class="fas fa-rocket me-2"></i>
                    <?= htmlspecialchars(Config::get('app.name', 'My CMS Site')) ?>
                </h5>
                <p class="text-muted">
                    <?= htmlspecialchars(Config::get('app.description', 'A powerful PHP CMS built with MVC architecture')) ?>
                </p>
            </div>

            <div class="col-md-4 mb-4">
                <h6>Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="/" class="text-muted text-decoration-none">Home</a></li>
                    <li><a href="/admin" class="text-muted text-decoration-none">Admin Panel</a></li>
                    <li><a href="/sitemap.xml" class="text-muted text-decoration-none">Sitemap</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h6>Connect</h6>
                <div class="d-flex">
                    <a href="#" class="text-muted me-3" title="Facebook">
                        <i class="fab fa-facebook fa-lg"></i>
                    </a>
                    <a href="#" class="text-muted me-3" title="Twitter">
                        <i class="fab fa-twitter fa-lg"></i>
                    </a>
                    <a href="#" class="text-muted me-3" title="LinkedIn">
                        <i class="fab fa-linkedin fa-lg"></i>
                    </a>
                    <a href="#" class="text-muted" title="GitHub">
                        <i class="fab fa-github fa-lg"></i>
                    </a>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-muted mb-0">
                    © <?= date('Y') ?> <?= htmlspecialchars(Config::get('app.name', 'My CMS Site')) ?>. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">
                    Powered by Custom PHP MVC Framework
                </small>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Theme Scripts -->
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading animation to forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                submitBtn.disabled = true;
            }
        });
    });

    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    });
</script>

<!-- Additional JavaScript from theme -->
<?php if (isset($additional_js)): ?>
    <?php foreach ($additional_js as $js): ?>
        <script src="<?= $js ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Inline JavaScript -->
<?php if (isset($inline_js)): ?>
    <script>
        <?= $inline_js ?>
    </script>
<?php endif; ?>
</body>

</html>