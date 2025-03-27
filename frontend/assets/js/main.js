import { getTasks, createTask, updateTask, deleteTask, toggleTaskStatus } from "./api.js";
import { validateTask } from "./validation.js";

document.addEventListener("DOMContentLoaded", () => {
    loadTasks();

    document.getElementById("taskForm").addEventListener("submit", async (e) => {
        e.preventDefault();

        const id = document.getElementById("taskId").value;
        const task = {
            title: document.getElementById("title").value.trim(),
            description: document.getElementById("description").value.trim(),
            status: document.getElementById("status").value
        };

        const errors = validateTask(task, !id);
        if (Object.keys(errors).length > 0) {
            alert(Object.values(errors).join("\n"));
            return;
        }

        if (id) {
            await updateTask(id, task);
        } else {
            await createTask(task);
        }

        document.getElementById("taskForm").reset();
        document.getElementById("taskId").value = "";
        loadTasks();
    });
});

async function loadTasks() {
    const tasks = await getTasks();
    const taskList = document.getElementById("taskList");
    taskList.innerHTML = "";

    tasks.forEach(task => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${task.title}</td>
            <td>${task.description}</td>
            <td>
                <span class="badge ${getStatusClass(task.status)}">${formatStatus(task.status)}</span>
            </td>
            <td>
                <button class="btn btn-sm btn-success toggle-btn" 
                    data-id="${task.id}" 
                    data-title="${task.title}" 
                    data-description="${task.description}" 
                    data-status="${task.status}">
                    ${task.status === 'completed' ? 'Reabrir' : 'Concluir'}
                </button>
                <button class="btn btn-sm btn-primary edit-btn" data-id="${task.id}">
                    Editar
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${task.id}">
                    Excluir
                </button>
            </td>
        `;

        row.querySelector(".toggle-btn").addEventListener("click", async (e) => {
            const button = e.target;
            const id = button.getAttribute("data-id");
            const title = button.getAttribute("data-title");
            const description = button.getAttribute("data-description");
            const currentStatus = button.getAttribute("data-status");
            
            const newStatus = currentStatus === "completed" ? "pending" : "completed";

            // Chama a função para atualizar a tarefa com o novo status
            await toggleTaskStatus(id, newStatus, title, description);
            loadTasks();
        });

        row.querySelector(".edit-btn").addEventListener("click", () => {
            document.getElementById("taskId").value = task.id;
            document.getElementById("title").value = task.title;
            document.getElementById("description").value = task.description;
            document.getElementById("status").value = task.status;
        });

        row.querySelector(".delete-btn").addEventListener("click", async () => {
            await deleteTask(task.id);
            loadTasks();
        });

        taskList.appendChild(row);
    });
}

// Função de Cor
function getStatusClass(status) {
    if (status === "completed") {
        return "bg-success";
    } else if (status === "in_progress") {
        return "bg-warning";
    } else {
        return "bg-secondary";
    }
}

function formatStatus(status) {
    if (status === "completed") {
        return "Concluída";
    } else if (status === "in_progress") {
        return "Em Andamento";
    } else {
        return "Pendente";
    }
}
