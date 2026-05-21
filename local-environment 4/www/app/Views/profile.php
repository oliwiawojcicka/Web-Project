<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="feed-container container">

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <section class="create-post-box">
        <h2><?= lang('App.profile_title') ?></h2>

        <?php if (! empty($user['profile_pic']) && $user['profile_pic'] !== 'default.png'): ?>
            <img src="<?= base_url($user['profile_pic']) ?>" alt="Profile picture" class="profile-pic-preview">
        <?php else: ?>
            <div class="avatar avatar-large"><?= esc(strtoupper(substr($user['username'] ?? 'U', 0, 1))) ?></div>
        <?php endif; ?>

        <form action="/profile" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <label for="username"><?= lang('App.label_username') ?></label>
            <input type="text" id="username" name="username" value="<?= esc($user['username'] ?? '') ?>" required>
            <?php if (session('errors.username')): ?>
                <span class="field-error"><?= esc(session('errors.username')) ?></span>
            <?php endif; ?>

            <label for="email"><?= lang('App.label_email_readonly') ?></label>
            <input type="email" id="email" value="<?= esc($user['email'] ?? '') ?>" readonly>

            <label for="profile_pic"><?= lang('App.label_change_pic') ?></label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*">

            <label for="password"><?= lang('App.label_new_password') ?> <span class="optional">(<?= lang('App.password_hint') ?>)</span></label>
            <input type="password" id="password" name="password">
            <?php if (session('errors.password')): ?>
                <span class="field-error"><?= esc(session('errors.password')) ?></span>
            <?php endif; ?>

            <div class="post-actions">
                <button type="submit" class="btn btn-primary"><?= lang('App.btn_save') ?></button>
            </div>
        </form>
    </section>

    <section class="feed-heading">
        <h2><?= lang('App.your_posts') ?></h2>
    </section>

    <section class="posts">
        <?php if (! empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <p class="post-content"><?= esc($post['content']) ?></p>
                    <?php if (! empty($post['image'])): ?>
                        <img class="post-image" src="<?= base_url($post['image']) ?>" alt="Post image">
                    <?php endif; ?>
                    <small style="color:var(--text-muted)"><?= esc($post['created_at']) ?></small>
                    <div class="post-actions">
                        <a href="/post/edit/<?= esc($post['id']) ?>" class="btn btn-secondary"><?= lang('App.btn_edit') ?></a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-comments"><?= lang('App.no_posts_yet') ?></p>
        <?php endif; ?>
    </section>

    <section class="create-post-box danger-zone">
        <h2><?= lang('App.danger_zone') ?></h2>
        <p><?= lang('App.danger_desc') ?></p>
        <form action="/profile/delete" method="post"
              onsubmit="return confirm('<?= lang('App.confirm_delete_account') ?>')">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger"><?= lang('App.btn_delete_account') ?></button>
        </form>
    </section>

</div>
<?= $this->endSection() ?>
