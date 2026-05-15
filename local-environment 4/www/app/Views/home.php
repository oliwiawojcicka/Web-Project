<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | LSMiniSocial</title>
    <link rel="stylesheet" href="/assets/css/home.css">
</head>
<body>
<nav class="navbar">
    <h1>LSMiniSocial</h1>
    <div>
        <a href="/profile">Profile</a>
        <a href="/post/create">Create Post</a>
        <a href="/logout">Logout</a>
    </div>
</nav>

<main class="feed-container">
    <section class="create-post-box">
        <h2>Create a new post</h2>
        <textarea placeholder="What are you thinking about?"></textarea>
        <button>Post</button>
    </section>

    <section class="posts">
        <article class="post-card">
            <div class="post-header">
                <div class="avatar">O</div>
                <div>
                    <h3>oliwia</h3>
                    <p>Today</p>
                </div>
            </div>

            <p class="post-content">
                This is an example post for the LSMiniSocial feed.
            </p>

            <div class="post-actions">
                <button>♡ Like</button>
                <button>💬 Comment</button>
            </div>

            <p class="post-stats">0 likes · 0 comments</p>
        </article>

        <article class="post-card">
            <div class="post-header">
                <div class="avatar">L</div>
                <div>
                    <h3>lasalle_user</h3>
                    <p>Yesterday</p>
                </div>
            </div>

            <p class="post-content">
                Welcome to LSMiniSocial!
            </p>

            <div class="post-actions">
                <button>♡ Like</button>
                <button>💬 Comment</button>
            </div>

            <p class="post-stats">3 likes · 1 comment</p>
        </article>
    </section>
</main>
</body>
</html>
