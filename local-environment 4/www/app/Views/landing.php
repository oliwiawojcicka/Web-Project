<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to LSMiniSocial</h1>

            <p>
                Connect with the La Salle community,
                create posts and improve them with AI.
            </p>

            <div class="hero-buttons">
                <a href="/sign-in" class="btn btn-primary">
                    Sign In
                </a>

                <a href="/sign-up" class="btn btn-secondary">
                    Create Account
                </a>
            </div>
        </div>
    </section>

    <section class="features container">

        <article class="feature-card">
            <h2>Create posts</h2>

            <p>
                Share your ideas and thoughts with students.
            </p>
        </article>

        <article class="feature-card">
            <h2>Interact</h2>

            <p>
                Like and comment on posts from the community.
            </p>
        </article>

        <article class="feature-card">
            <h2>Improve with AI</h2>

            <p>
                Make your posts better before publishing them.
            </p>
        </article>

    </section>

<?= $this->endSection() ?>