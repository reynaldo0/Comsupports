const chatBox         = document.getElementById('chat-box');
const userInput       = document.getElementById('user-input');
const sendBtn         = document.getElementById('send-btn');
const typingIndicator = document.getElementById('typing-indicator');
const csrfToken       = document.querySelector('meta[name="csrf-token"]').content;

function appendMessage(text, sender) {
    const isUser  = sender === 'user';
    const wrapper = document.createElement('div');
    wrapper.className = 'message flex items-end gap-2.5' + (isUser ? ' flex-row-reverse' : '');

    if (!isUser) {
        const avatar = document.createElement('div');
        avatar.className = 'flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/30';
        avatar.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>';
        wrapper.appendChild(avatar);
    }

    const bubble = document.createElement('div');
    bubble.className = 'max-w-[78%] px-4 py-3 rounded-2xl text-sm leading-relaxed shadow-sm '
        + (isUser
            ? 'bg-blue-600 text-white rounded-br-md'
            : 'bg-gray-100 text-gray-800 rounded-bl-md');
    bubble.textContent = text;

    wrapper.appendChild(bubble);
    chatBox.appendChild(wrapper);
    chatBox.scrollTop = chatBox.scrollHeight;
}

async function sendMessage() {
    const message = userInput.value.trim();
    if (!message) return;

    appendMessage(message, 'user');
    userInput.value = '';
    sendBtn.disabled = true;
    typingIndicator.classList.remove('hidden');
    chatBox.scrollTop = chatBox.scrollHeight;

    try {
        const response = await fetch('/chat/send', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ message }),
        });
        const data = await response.json();
        typingIndicator.classList.add('hidden');
        appendMessage(data.reply, 'bot');
    } catch (error) {
        typingIndicator.classList.add('hidden');
        appendMessage('Maaf, terjadi kesalahan. Coba lagi.', 'bot');
    } finally {
        sendBtn.disabled = false;
        userInput.focus();
    }
}

sendBtn.addEventListener('click', sendMessage);
userInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});

document.querySelectorAll('#quick-suggestions button').forEach((btn) => {
    btn.addEventListener('click', () => {
        userInput.value = btn.dataset.question;
        sendMessage();
    });
});
