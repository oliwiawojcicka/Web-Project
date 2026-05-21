<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<section class="auth-wrapper">
    <div class="auth-card">
        <h1><?= lang('App.sign_in_title') ?></h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>
        <?php if (session('errors.login')): ?>
            <div class="alert-error"><?= esc(session('errors.login')) ?></div>
        <?php endif; ?>

        <form action="/sign-in" method="post">
            <?= csrf_field() ?>

            <label for="email"><?= lang('App.label_email') ?></label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
            <?php if (session('errors.email')): ?>
                <span class="field-error"><?= esc(session('errors.email')) ?></span>
            <?php endif; ?>

            <label for="password"><?= lang('App.label_password') ?></label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn btn-primary full-width"><?= lang('App.btn_sign_in') ?></button>
        </form>

        <p class="auth-link">
            <?= lang('App.auth_no_account') ?>
            <a href="/sign-up"><?= lang('App.auth_create_one') ?></a>
        </p>
    </div>
</section>

<?= $this->endSection() ?>
