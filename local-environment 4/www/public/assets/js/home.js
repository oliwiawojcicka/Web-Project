// Home page interactions — likes, comments, delete post, AI improve
(function () {

    function csrfHeaders() {
        return {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            [CSRF_NAME]: CSRF_HASH,
        };
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Like / Unlike ─────────────────────────────────────────────────────────
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const postId = btn.dataset.postId;
            const liked  = btn.dataset.liked === '1';
            const method = liked ? 'DELETE' : 'POST';

            try {
                const res  = await fetch(`/posts/${postId}/like`, { method, headers: csrfHeaders() });
                const data = await res.json();

                if (data.likes_count !== undefined) {
                    btn.closest('.post-card').querySelector('.likes-count').textContent = data.likes_count;
                    btn.dataset.liked = liked ? '0' : '1';
                    btn.textContent   = liked ? '🤍 Like' : '❤️ Unlike';
                    btn.classList.toggle('liked', !liked);
                }
            } catch (e) {
                console.error('Like error:', e);
            }
        });
    });

    // ── Toggle comments box ───────────────────────────────────────────────────
    document.querySelectorAll('.toggle-comments-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const box = document.getElementById('comments-box-' + btn.dataset.postId);
            box.style.display = box.style.display === 'none' ? 'block' : 'none';
        });
    });

    // ── Submit comment ────────────────────────────────────────────────────────
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const postId  = form.dataset.postId;
            const input   = form.querySelector('input[name="content"]');
            const content = input.value.trim();
            if (!content) return;

            try {
                const res  = await fetch(`/posts/${postId}/comments`, {
                    method: 'POST',
                    headers: csrfHeaders(),
                    body: JSON.stringify({ content }),
                });
                const data = await res.json();

                if (data.comment) {
                    const noComments = document.getElementById('no-comments-' + postId);
                    if (noComments) noComments.remove();

                    const list = document.getElementById('comments-list-' + postId);
                    const div  = document.createElement('div');
                    div.className         = 'comment';
                    div.dataset.commentId = data.comment.id;
                    div.innerHTML = `
                        <strong>${escHtml(data.comment.username ?? 'User')}</strong>
                        <p>${escHtml(data.comment.content)}</p>
                        <small>${escHtml(data.comment.created_at ?? '')}</small>
                        <button type="button" class="btn-link delete-comment-btn"
                            data-comment-id="${data.comment.id}"
                            data-post-id="${postId}">Delete</button>
                    `;
                    list.appendChild(div);
                    attachDeleteComment(div.querySelector('.delete-comment-btn'));

                    input.value = '';
                    form.closest('.post-card').querySelector('.comments-count').textContent = data.comments_count;
                }
            } catch (e) {
                console.error('Comment error:', e);
            }
        });
    });

    // ── Delete comment ────────────────────────────────────────────────────────
    function attachDeleteComment(btn) {
        btn.addEventListener('click', async () => {
            const commentId = btn.dataset.commentId;
            const postId    = btn.dataset.postId;
            if (!confirm('Delete this comment?')) return;

            try {
                const res  = await fetch(`/comments/${commentId}`, { method: 'DELETE', headers: csrfHeaders() });
                const data = await res.json();

                if (data.success) {
                    btn.closest('.comment').remove();
                    document.querySelector(`[data-post-id="${postId}"] .comments-count`).textContent = data.comments_count;
                }
            } catch (e) {
                console.error('Delete comment error:', e);
            }
        });
    }
    document.querySelectorAll('.delete-comment-btn').forEach(attachDeleteComment);

    // ── Delete post ───────────────────────────────────────────────────────────
    document.querySelectorAll('.delete-post-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Delete this post?')) return;
            const postId = btn.dataset.postId;

            try {
                const res  = await fetch(`/posts/${postId}`, { method: 'DELETE', headers: csrfHeaders() });
                const data = await res.json();
                if (data.success) {
                    btn.closest('.post-card').remove();
                }
            } catch (e) {
                console.error('Delete post error:', e);
            }
        });
    });

    // ── AI Improve (home textarea) ────────────────────────────────────────────
    const aiBtn        = document.getElementById('ai-improve-btn');
    const aiBox        = document.getElementById('ai-suggestion-box');
    const aiText       = document.getElementById('ai-suggestion-text');
    const aiAccept     = document.getElementById('ai-accept-btn');
    const aiReject     = document.getElementById('ai-reject-btn');
    const homeTextarea = document.getElementById('home-content');

    if (aiBtn && homeTextarea) {
        aiBtn.addEventListener('click', async () => {
            const text = homeTextarea.value.trim();
            if (!text) { alert('Write something first.'); return; }

            aiBtn.textContent = 'Improving...';
            aiBtn.disabled    = true;

            try {
                const res  = await fetch('/ai/improve', {
                    method: 'POST',
                    headers: csrfHeaders(),
                    body: JSON.stringify({ text }),
                });
                const data = await res.json();

                if (data.improved_text) {
                    aiText.textContent  = data.improved_text;
                    aiBox.style.display = 'block';
                } else {
                    alert(data.error ?? 'AI error.');
                }
            } catch (e) {
                alert('Network error. Please try again.');
            } finally {
                aiBtn.textContent = '✨ Improve with AI';
                aiBtn.disabled    = false;
            }
        });

        aiAccept.addEventListener('click', () => {
            homeTextarea.value  = aiText.textContent;
            aiBox.style.display = 'none';
        });

        aiReject.addEventListener('click', () => {
            aiBox.style.display = 'none';
        });
    }

})();