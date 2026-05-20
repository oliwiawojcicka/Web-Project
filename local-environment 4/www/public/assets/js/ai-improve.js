document.addEventListener('DOMContentLoaded', () => {
    const aiButtons = document.querySelectorAll('[data-ai-improve]');

    aiButtons.forEach((button) => {
        button.addEventListener('click', async () => {
            const targetId = button.dataset.target;
            const textarea = document.getElementById(targetId);

            if (!textarea) {
                return;
            }

            const originalText = textarea.value.trim();

            if (!originalText) {
                showSuggestion(button, 'Please write something before using AI.', false);
                return;
            }

            button.disabled = true;
            button.textContent = 'Improving...';

            try {
                const response = await fetch('/ai/improve', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        text: originalText
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.improved_text) {
                    showSuggestion(button, data.error || 'AI suggestion is not available yet.', false);
                    return;
                }

                showSuggestion(button, data.improved_text, true, textarea);
            } catch (error) {
                showSuggestion(button, 'AI service is not connected yet.', false);
            } finally {
                button.disabled = false;
                button.textContent = 'Improve with AI';
            }
        });
    });
});

function showSuggestion(button, message, canAccept, textarea = null) {
    const form = button.closest('form');
    let box = form.querySelector('.ai-suggestion-box');

    if (!box) {
        box = document.createElement('div');
        box.className = 'ai-suggestion-box';
        form.insertBefore(box, form.querySelector('.post-actions'));
    }

    box.innerHTML = `
        <h3>AI suggestion</h3>
        <p>${escapeHtml(message)}</p>
        ${
        canAccept
            ? `<div class="post-actions">
                    <button type="button" class="secondary-button" data-ai-accept>Accept suggestion</button>
                    <button type="button" class="secondary-button" data-ai-reject>Reject suggestion</button>
                   </div>`
            : ''
    }
    `;

    const acceptButton = box.querySelector('[data-ai-accept]');
    const rejectButton = box.querySelector('[data-ai-reject]');

    if (acceptButton && textarea) {
        acceptButton.addEventListener('click', () => {
            textarea.value = message;
            box.remove();
        });
    }

    if (rejectButton) {
        rejectButton.addEventListener('click', () => {
            box.remove();
        });
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}