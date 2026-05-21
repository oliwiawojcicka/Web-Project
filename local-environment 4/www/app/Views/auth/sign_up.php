<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<section class="auth-wrapper">
    <div class="auth-card">
        <h1><?= lang('App.sign_up_title') ?></h1>

        <form action="/sign-up" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <label for="username"><?= lang('App.label_username') ?> <span class="optional">(<?= lang('App.optional') ?>)</span></label>
            <input type="text" id="username" name="username" value="<?= old('username') ?>">

            <label for="profile_pic"><?= lang('App.label_profile_pic') ?> <span class="optional">(<?= lang('App.optional') ?>)</span></label>
            <input type="file" id="profile_pic" name="profile_pic" accept="image/*">

            <label for="email"><?= lang('App.label_email') ?></label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            <?php if (session('errors.email')): ?>
                <span class="field-error"><?= esc(session('errors.email')) ?></span>
            <?php endif; ?>

            <label for="password"><?= lang('App.label_password') ?></label>
            <input type="password" id="password" name="password" required>
            <?php if (session('errors.password')): ?>
                <span class="field-error"><?= esc(session('errors.password')) ?></span>
            <?php endif; ?>

            <label for="repeat_password"><?= lang('App.label_repeat_password') ?></label>
            <input type="password" id="repeat_password" name="repeat_password" required>
            <?php if (session('errors.repeat_password')): ?>
                <span class="field-error"><?= esc(session('errors.repeat_password')) ?></span>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary full-width"><?= lang('App.btn_sign_up') ?></button>
        </form>

        <p class="auth-link">
            <?= lang('App.auth_have_account') ?>
            <a href="/sign-in"><?= lang('App.auth_sign_in_link') ?></a>
        </p>
    </div>
</section>

<?= $this->endSection() ?>
