<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile | LSMiniSocial</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">
</head>
<body>
<nav class="navbar">
    <h1>LSMiniSocial</h1>
    <div>
        <a href="/home">Home</a>
        <a href="/post/create">Create Post</a>
        <a href="/profile" class="active">Profile</a>
        <a href="/logout">Logout</a>
    </div>
</nav>

<main class="feed-container">
    <section class="create-post-box">
        <h2>Your Profile</h2>
        <p>Manage your public information and keep your LSMiniSocial account up to date.</p>

        <form>
            <label for="username">Username</label>
            <input id="username" type="text" placeholder="Your username">

            <label for="email">Email</label>
            <input id="email" type="email" placeholder="your.email@students.salle.url.edu" readonly>

            <label for="picture">Profile picture</label>
            <input id="picture" type="file">

            <button type="submit">Save changes</button>
        </form>
    </section>
</main>
</body>
</html>
