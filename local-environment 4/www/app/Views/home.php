<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | LSMiniSocial</title>
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
        <h2>Create a new post</h2>
        <p>Share an update with the La Salle community directly from your homepage.</p>

        <form action="/posts" method="post" enctype="multipart/form-data">
            <label for="home-content">Post content</label>
            <textarea id="home-content" name="content" required placeholder="What are you thinking about?"><?= old('content') ?></textarea>

            <label for="home-image">Optional image</label>
            <input id="home-image" type="file" name="image">

            <div class="post-actions">
                <button type="button" class="secondary-button">Improve with AI</button>
                <button type="submit">Publish post</button>
            </div>
        </form>
    </section>

    <section class="feed-heading">
        <h2>Community Feed</h2>
        <p>Most recent posts from the La Salle community.</p>
    </section>

    <section class="posts">
        <?php if (! empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <div class="post-header">
                        <div class="avatar">
                            <?= esc(strtoupper(substr($post['username'] ?? 'U', 0, 1))) ?>
                        </div>

                        <div>
                            <h3><?= esc($post['username'] ?? 'Unknown user') ?></h3>
                            <p>Created <?= esc($post['created_at'] ?? '') ?></p>
                        </div>
                    </div>

                    <p class="post-content">
                        <?= esc($post['content']) ?>
                    </p>

                    <?php if (! empty($post['image'])): ?>
                        <img
                                class="post-image"
                                src="<?= base_url($post['image']) ?>"
                                alt="Post image"
                        >
                    <?php endif; ?>

                    <p class="post-stats">
                        <?= esc($post['likes_count'] ?? 0) ?> likes ·
                        <?= esc($post['comments_count'] ?? 0) ?> comments
                    </p>

                    <div class="post-actions">
                        <button type="button" class="secondary-button">♡ Like</button>
                        <button type="button" class="secondary-button">💬 Comment</button>
                    </div>

                    <?php if ((int) ($post['user_id'] ?? 0) === (int) session()->get('user_id')): ?>
                        <div class="owner-actions">
                            <a href="/post/edit/<?= esc($post['id']) ?>" class="button-link secondary-button">Edit</a>

                            <form action="/posts/<?= esc($post['id']) ?>" method="post">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="danger-button">Delete</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <div class="comments-box">
                        <h4>Comments</h4>

                        <?php if (! empty($post['comments'])): ?>
                            <?php foreach ($post['comments'] as $comment): ?>
                                <div class="comment">
                                    <strong><?= esc($comment['username'] ?? 'User') ?></strong>
                                    <p><?= esc($comment['content']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="empty-comments">No comments yet.</p>
                        <?php endif; ?>

                        <form action="/posts/<?= esc($post['id']) ?>/comments" method="post" class="comment-form">
                            <input type="text" name="content" required placeholder="Write a comment...">
                            <button type="submit">Send</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <article class="post-card">
                <h2>No posts yet</h2>
                <p class="empty-comments">
                    Be the first person to share something with the La Salle community.
                </p>
                <a href="/post/create" class="button-link">Create your first post</a>
            </article>
        <?php endif; ?>
    </section>
</main>
</body>
</html>