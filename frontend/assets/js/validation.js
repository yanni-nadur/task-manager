export function validateTask(task, isNew = true) {
    const errors = {};

    if (!task.title || task.title.trim().length < 3) {
        errors.title = "O título deve ter pelo menos 3 caracteres.";
    }
    if (!task.description || task.description.trim().length < 5) {
        errors.description = "A descrição deve ter pelo menos 5 caracteres.";
    }
    if (isNew && task.status === "completed") {
        errors.status = "Não é permitido criar uma tarefa como 'Concluída'.";
    }

    return errors;
}
