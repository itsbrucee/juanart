<?php $pageTitle = 'Categories'; ob_start(); ?>
<div class="container">
    <h1>Categories</h1>
    <form method="post" class="form form-inline">
        <input type="hidden" name="action" value="category_add">
        <input type="text" name="name" placeholder="New category" class="input" required>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
    <table class="table">
        <thead><tr><th>ID</th><th>Name</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?= (int)$c['id'] ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="category_edit">
                            <input type="hidden" name="category_id" value="<?= (int)$c['id'] ?>">
                            <input type="text" name="name" value="<?= htmlspecialchars($c['name']) ?>" class="input input-sm">
                            <button type="submit" class="btn btn-sm">Save</button>
                        </form>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="category_delete">
                            <input type="hidden" name="category_id" value="<?= (int)$c['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
