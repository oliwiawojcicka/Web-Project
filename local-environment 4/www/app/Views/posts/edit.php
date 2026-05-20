<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post | LSMiniSocial</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">
</head>
<body>
<nav class="navbar">
    <h1>LSMiniSocial</h1>
    <div>
        <a href="/home" class="<?= uri_string() === 'home' ? 'active' : '' ?>">Home</a>
        <a href="/post/create" class="<?= uri_string() === 'post/create' ? 'active' : '' ?>">Create Post</a>
        <a href="/profile" class="<?= uri_string() === 'profile' ? 'active' : '' ?>">Profile</a>
        <a href="/logout">Logout</a>
    </div>
</nav>

<main class="feed-container">

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert-error">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <section class="create-post-box">
        <h2>Edit post</h2>
        <p>Update the content of your post. Only the owner of the post should be allowed to edit it.</p>

        <form action="/posts/<?= esc($post['id'] ?? $postId ?? '') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_method" value="PUT">

            <label for="content">Post content</label>
            <textarea id="content" name="content" required><?= esc($post['content'] ?? old('content') ?? ('This is the current content of post #' . ($postId ?? ''))) ?></textarea>

            <label for="image">Change image optional</label>
            <input id="image" type="file" name="image">

            <?php if (! empty($post['image'])): ?>
                <img
                        class="post-image"
                        src="<?= base_url($post['image']) ?>"
                        alt="Current post image"
                >
            <?php endif; ?>

            <div class="post-actions">
                <button type="button" class="secondary-button" data-ai-improve data-target="content">
                    Improve with AI
                </button>
                <button type="submit">Save changes</button>
                <a href="/home" class="button-link secondary-button">Cancel</a>
            </div>
        </form>
    </section>
</main>

<script src="<?= base_url('assets/js/ai-improve.js') ?>"></script>
</body>
</html>
