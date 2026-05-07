<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

    <section class="auth-wrapper">

        <div class="auth-card">

            <h1>Create account</h1>

            <form
                action="/sign-up"
                method="post"
                enctype="multipart/form-data"
            >

                <?= csrf_field() ?>

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    value="<?= old('username') ?>"
                >

                <label>Profile picture</label>

                <input
                    type="file"
                    name="profile_picture"
                >

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="<?= old('email') ?>"
                    required
                >

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    required
                >

                <label>Repeat password</label>

                <input
                    type="password"
                    name="repeat_password"
                    required
                >

                <button type="submit" class="btn btn-primary full-width">
                    Sign Up
                </button>

            </form>

            <p class="auth-link">
                Already have an account?
                <a href="/sign-in">Sign in</a>
            </p>

        </div>

    </section>

<?= $this->endSection() ?>