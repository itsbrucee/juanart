<?php $pageTitle = 'Users'; ob_start(); ?>
<div class="container">
    <h1>Users</h1>
    <table class="table">
        <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= (int)$u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role_name']) ?></td>
                    <td><?= htmlspecialchars($u['status']) ?></td>
                    <td>
                        <?php if ($u['status'] === 'active'): ?>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="action" value="user_suspend">
                                <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Suspend</button>
                            </form>
                        <?php else: ?>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="action" value="user_activate">
                                <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                <button type="submit" class="btn btn-sm">Activate</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layout.php';
