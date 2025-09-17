const form = document.getElementById('form-tarefa');
const lista = document.getElementById('lista-tarefas');
const contador = document.getElementById('contador');

// Atualiza contador de tarefas pendentes
function atualizarContador() {
    const qtd = document.querySelectorAll('#lista-tarefas li.pendente').length;
    contador.textContent = `Tarefas pendentes: ${qtd}`;
}

// Adicionar tarefa via AJAX
form.addEventListener('submit', function(e) {
    e.preventDefault();
    const input = form.querySelector('input[name="descricao"]');
    const descricao = input.value.trim();
    if (descricao === "") return;

    const formData = new FormData();
    formData.append('descricao_ajax', descricao);

    fetch('index.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        const li = document.createElement('li');
        li.setAttribute('data-id', data.id);
        li.classList.add('pendente');
        li.innerHTML = `${data.descricao} <span class="delete">[X]</span>`;
        li.style.opacity = 0;
        lista.appendChild(li);
        setTimeout(() => li.style.opacity = 1, 10);
        input.value = '';
        attachDelete(li.querySelector('.delete'));
        attachComplete(li);
        atualizarContador();
    });
});

// Remover tarefa via AJAX com animação
function attachDelete(btn) {
    btn.addEventListener('click', function() {
        const li = this.parentElement;
        const id = li.getAttribute('data-id');

        const formData = new FormData();
        formData.append('delete_id', id);

        fetch('index.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                li.style.transition = 'opacity 0.4s, transform 0.4s';
                li.style.opacity = 0;
                li.style.transform = 'translateX(50px)';
                setTimeout(() => { li.remove(); atualizarContador(); }, 400);
            }
        });
    });
}

// Marcar tarefa como concluída
function attachComplete(li) {
    li.addEventListener('click', function(e) {
        if(e.target.classList.contains('delete')) return;
        li.classList.toggle('concluida');
        li.classList.toggle('pendente');
        atualizarContador();
    });
}

// Inicializa delete e complete nas tarefas existentes
document.querySelectorAll('#lista-tarefas li').forEach(li => {
    attachDelete(li.querySelector('.delete'));
    attachComplete(li);
});

atualizarContador();
