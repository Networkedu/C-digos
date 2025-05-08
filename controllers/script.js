
let tasks = JSON.parse(localStorage.getItem('profTasks') || '[]');
let currentTaskId = null;

function renderTasks() {
  const list = document.getElementById('taskList');
  list.innerHTML = '';
  if (tasks.length === 0) {
    const emptyMsg = document.createElement('li');
    emptyMsg.textContent = 'No hay tareas creadas.';
    list.appendChild(emptyMsg);
  } else {
    tasks.forEach((task, index) => {
      const li = document.createElement('li');
      li.innerHTML = `
        ${task.name}
        <span style="cursor:pointer;" onclick="editTask(${index})">✏️</span>
        <span style="cursor:pointer;" onclick="deleteTask(${index})">🗑️</span>
      `;
      list.appendChild(li);
    });
  }
}

function prepareNewTask() {
  clearForm();
  currentTaskId = null;
  console.log('Preparando nueva tarea...');
}

function clearForm() {
  document.getElementById('taskName').value = '';
  document.getElementById('taskDate').value = '';
  document.getElementById('taskStudents').value = '';
  document.getElementById('taskSubject').value = '';
}

function saveTask() {
  const name = document.getElementById('taskName').value.trim();
  const date = document.getElementById('taskDate').value;
  const students = document.getElementById('taskStudents').value.trim();
  const subject = document.getElementById('taskSubject').value.trim();

  if (!name || !date || !students || !subject) {
    alert('Por favor, completa todos los campos.');
    return;
  }

  const taskData = { name, date, students, subject };

  if (currentTaskId !== null) {
    tasks[currentTaskId] = taskData;
    console.log('Tarea actualizada:', taskData);
  } else {
    tasks.push(taskData);
    console.log('Nueva tarea guardada:', taskData);
  }

  localStorage.setItem('profTasks', JSON.stringify(tasks));
  renderTasks();
  clearForm();
  currentTaskId = null;
}

function editTask(index) {
  const task = tasks[index];
  document.getElementById('taskName').value = task.name;
  document.getElementById('taskDate').value = task.date;
  document.getElementById('taskStudents').value = task.students;
  document.getElementById('taskSubject').value = task.subject;
  currentTaskId = index;
  console.log('Editando tarea:', task);
}

function deleteTask(index) {
  if (confirm('¿Estás seguro de eliminar esta tarea?')) {
    tasks.splice(index, 1);
    localStorage.setItem('profTasks', JSON.stringify(tasks));
    renderTasks();
    clearForm();
    currentTaskId = null;
  }
}

// LOGIN
function loginUser() {
  const role = document.getElementById('role').value;
  if (role === 'alumno') {
    window.location.href = 'alumno.html';
  } else {
    window.location.href = 'profesor.html';
  }
  return false;
}

function registerUser() {
  const email = document.getElementById('regEmail')?.value || '';
  const name = email.split('@')[0];
  alert('Cuenta creada para ' + name);
  return false;
}

window.addEventListener('DOMContentLoaded', () => {
  renderTasks();

  const urlParams = new URLSearchParams(window.location.search);
  const role = urlParams.get('role');
  if (role) {
    const select = document.getElementById('role');
    if (select) select.value = role;
  }
});
