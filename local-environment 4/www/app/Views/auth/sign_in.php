<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

    <section class="auth-wrapper">

        <div class="auth-card">

            <h1>Sign In</h1>

            <?php if (session('errors.login')) : ?>
                <div class="error-message">
                    <?= session('errors.login') ?>
                </div>
            <?php endif ?>

            <form action="/sign-in" method="post">

                <?= csrf_field() ?>

                <label>Email</label>

                <input
                        type="email"
                        name="email"
                        value="<?= old('email') ?>"
                        required
                >
                <?php if (session('errors.email')) : ?>
                    <span class="error-message"><?= session('errors.email') ?></span>
                <?php endif ?>

                <label>Password</label>

                <input
                        type="password"
                        name="password"
                        required
                >

                <button type="submit" class="btn btn-primary full-width">
                    Sign In
                </button>

            </form>

            <p class="auth-link">
                Don’t have an account?
                <a href="/sign-up">Create one</a>
            </p>

        </div>

    </section>

<?= $this->endSection() ?>