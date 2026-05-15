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
    <section class="create-post-box">
        <h2>Create a new post</h2>
        <p>Share an update with the La Salle community directly from your homepage.</p>

        <form action="/posts" method="post" enctype="multipart/form-data">
            <label for="home-content">Post content</label>
            <textarea id="home-content" name="content" required placeholder="What are you thinking about?"></textarea>

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
        <article class="post-card">
            <div class="post-header">
                <div class="avatar">O</div>
                <div>
                    <h3>oliwia</h3>
                    <p>Created today · Most recent</p>
                </div>
            </div>

            <p class="post-content">
                This is an example post for the LSMiniSocial feed. Real posts will be loaded from the database and ordered by newest first.
            </p>

            <div class="post-image-placeholder">
                Optional post image
            </div>

            <p class="post-stats">3 likes · 2 comments</p>

            <div class="post-actions">
                <button type="button" class="secondary-button">♡ Like</button>
                <button type="button" class="secondary-button">💬 Comment</button>
            </div>

            <div class="owner-actions">
                <a href="/post/edit/1" class="button-link secondary-button">Edit</a>

                <form action="#" method="post">
                    <button type="button" class="danger-button">Delete</button>
                </form>
            </div>

            <div class="comments-box">
                <h4>Comments</h4>

                <div class="comment">
                    <strong>lasalle_user</strong>
                    <p>Nice post!</p>
                </div>

                <div class="comment">
                    <strong>student_22</strong>
                    <p>Welcome to the community feed.</p>
                </div>

                <form action="#" method="post" class="comment-form">
                    <input type="text" name="comment" required placeholder="Write a comment...">
                    <button type="submit">Send</button>
                </form>
            </div>
        </article>

        <article class="post-card">
            <div class="post-header">
                <div class="avatar">L</div>
                <div>
                    <h3>lasalle_user</h3>
                    <p>Created yesterday</p>
                </div>
            </div>

            <p class="post-content">
                Welcome to LSMiniSocial! This feed will display all posts created by users.
            </p>

            <p class="post-stats">1 like · 0 comments</p>

            <div class="post-actions">
                <button type="button" class="secondary-button">♡ Like</button>
                <button type="button" class="secondary-button">💬 Comment</button>
            </div>

            <div class="comments-box">
                <h4>Comments</h4>
                <p class="empty-comments">No comments yet.</p>

                <form action="#" method="post" class="comment-form">
                    <input type="text" name="comment" required placeholder="Write a comment...">
                    <button type="submit">Send</button>
                </form>
            </div>
        </article>
    </section>
</main>
</body>
</html>
