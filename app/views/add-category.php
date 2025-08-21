<h1>Add Category</h1>

<div>
    <?php if (isset($msg)): ?>
        <div class="alert alert-<?= $status ?>">
            <?= $msg ?>
        </div>
    <?php endif; ?>
    <form action="http://clycms.tw/Index/createCat/" method="post">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="" required>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="" required>
        <button type="submit">Save</button>
    </form>
</div>