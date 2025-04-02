let chats = [];
let chatsIdCounter = 0;

const nieuweBerichtInput = document.getElementById('nieuwe-taak');
const sendButtonInput = document.getElementById('maak-taak-btn');
const chatLijst = document.getElementById('taken-lijst');
const totaalLikesEl = document.getElementById('totaal-taken');
const totaalDislikesEl = document.getElementById('completen-taken');

function updateStats() {
    const total = chats.length;
    const completed = chats.filter(task => task.completed).length;

    totaalLikesEl.textContent = total.toString();
    totaalDislikesEl.textContent = completed.toString();
}

function renderTask(chat) {
    const li = document.createElement('li');
    li.className = `task ${chat.completed ? 'completed' : ''}`;
    li.dataset.id = chat.id;

    const taskText = document.createElement('span');
    taskText.textContent = chat.text;

    const completeButton = document.createElement('button');
    completeButton.textContent = chat.completed ? 'Resend' : 'Delete';
    completeButton.addEventListener('click', () => toggleTaskStatus(chat.id));
    completeButton.style.backgroundColor = "#52b860";
    completeButton.style.color = "white";
    completeButton.style.width = "8em";
    completeButton.style.height = "2em";
    completeButton.style.borderRadius = "3em";

    const deleteButton = document.createElement('button');
    deleteButton.textContent = 'Verwijderen';
    deleteButton.addEventListener('click', () => deleteTask(chat.id));
    deleteButton.style.backgroundColor = "#cf1e1e";
    deleteButton.style.color = "white";
    deleteButton.style.width = "8em";
    deleteButton.style.height = "2em";
    deleteButton.style.borderRadius = "3em";

    li.appendChild(taskText);
    li.appendChild(completeButton);
    li.appendChild(deleteButton);

    chatLijst.appendChild(li);
}

function refreshtakenLijst() {
    chatLijst.innerHTML = '';
    chats.forEach(renderTask);
    updateStats();
}

function maakTaak() {
    const taskText = nieuweBerichtInput.value;
    if (!taskText) return;

    const nieuwTaak = {
        id: chatsIdCounter++,
        text: taskText,
        completed: false,
    };

    chats.push(nieuwTaak);
    nieuweBerichtInput.value = '';
    refreshtakenLijst();
}

function toggleTaskStatus(id) {
    const chat = chats.find(t => t.id === id);
    if (chat) {
        chat.completed = !chats.completed;
        refreshtakenLijst();
    }
}

function deleteTask(id) {
    chats = chats.filter(t => t.id !== id);
    refreshtakenLijst();
}

sendButtonInput.addEventListener('click', maakTaak);
nieuweBerichtInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') {
        maakTaak();
    }
});

refreshtakenLijst();
