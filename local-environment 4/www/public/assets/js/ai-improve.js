// AI Improve — used on create.php and edit.php
(function () {
    const aiBtn    = document.getElementById('ai-improve-btn');
    const aiBox    = document.getElementById('ai-suggestion-box');
    const aiText   = document.getElementById('ai-suggestion-text');
    const aiAccept = document.getElementById('ai-accept-btn');
    const aiReject = document.getElementById('ai-reject-btn');
    const textarea = document.getElementById('content');

    if (!aiBtn || !textarea) return;

    function csrfHeaders() {
        return {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            [CSRF_NAME]: CSRF_HASH,
        };
    }

    aiBtn.addEventListener('click', async () => {
        const text = textarea.value.trim();
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
                alert(data.error ?? 'AI service error.');
            }
        } catch (e) {
            alert('Network error. Please try again.');
        } finally {
            aiBtn.textContent = '✨ Improve with AI';
            aiBtn.disabled    = false;
        }
    });

    aiAccept.addEventListener('click', () => {
        textarea.value      = aiText.textContent;
        aiBox.style.display = 'none';
    });

    aiReject.addEventListener('click', () => {
        aiBox.style.display = 'none';
    });
})();