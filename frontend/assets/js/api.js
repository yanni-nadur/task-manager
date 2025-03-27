const API_URL = "http://localhost:9000/tasks";
const AUTH_URL = "http://localhost:9000/login";

// Função para obter o token de autenticação (mockado)
async function getAuthToken() {
    const response = await fetch(AUTH_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username: "admin", password: "senha123" }),
    });

    if (!response.ok) {
        console.error("Erro ao obter token de autenticação");
        return null;
    }

    const data = await response.json();
    return data.token;
}

// Função para enviar requisições autenticadas
async function apiRequest(url, options = {}) {
    const token = await getAuthToken();
    if (!token) return null;

    const headers = {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
        ...options.headers,
    };

    const response = await fetch(url, { ...options, headers });

    if (!response.ok) {
        console.error(`Erro na requisição: ${response.statusText}`);
        return null;
    }

    return await response.json();
}

export async function getTasks() {
    return await apiRequest(API_URL);
}

export async function createTask(task) {
    return await apiRequest(API_URL, {
        method: "POST",
        body: JSON.stringify(task),
    });
}

export async function updateTask(id, task) {
    return await apiRequest(`${API_URL}/${id}`, {
        method: "PUT",
        body: JSON.stringify(task),
    });
}

export async function deleteTask(id) {
    return await apiRequest(`${API_URL}/${id}`, { method: "DELETE" });
}

// Função para alternar o status da tarefa (usa updateTask internamente)
export async function toggleTaskStatus(id, newStatus, title, description) {
    return await updateTask(id, { title, description, status: newStatus });
}
