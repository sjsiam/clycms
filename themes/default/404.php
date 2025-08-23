<?php
$page_title = 'Page Not Found - ' . Setting::get('site_title', 'ClyCMS');
$page_description = 'The page you are looking for could not be found.';

// Additional styles for 404 page
$additional_css = [];
$inline_css = "
        .error-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0 50px;
        }
        .error-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .error-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .share-buttons .btn {
            margin: 0 5px;
        }
";

include 'header.php';
?>

<style>
    <?= $inline_css ?>
</style>

<div class="error-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-5 mb-4">404 - Page Not Found</h1>
                <p class="lead">Oops! The page you're looking for doesn't exist or has been moved.</p>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="error-content">
                <p>We're sorry, but we can't find the page you were looking for. You can return to the homepage or explore our site using the links below.</p>
                <hr class="my-5">

                <!-- Navigation and Share Buttons -->
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <a href="/" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>