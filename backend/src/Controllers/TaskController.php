<?php
// Define o namespace para o controller
namespace Src\Controllers;

use Src\Services\DatabaseService;
use PDO;

class TaskController
{
    public function getTasks()
    {
        $db = DatabaseService::getConnection();
        $stmt = $db->query("SELECT * FROM tasks");
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($tasks);
    }

    public function createTask()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid request body']);
            return;
        }

        $title       = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $status      = $data['status'] ?? 'pending';

        if ($status === 'completed') {
            http_response_code(400);
            echo json_encode(['message' => 'Cannot create a task as completed']);
            return;
        }

        $created_at  = date('Y-m-d H:i:s');
        $updated_at  = date('Y-m-d H:i:s');

        $db = DatabaseService::getConnection();
        $stmt = $db->prepare("
            INSERT INTO tasks (title, description, status, created_at, updated_at)
            VALUES (:title, :description, :status, :created_at, :updated_at)
        ");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':created_at', $created_at);
        $stmt->bindParam(':updated_at', $updated_at);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'Task created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to create task']);
        }
    }

    public function updateTask($id)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid request body']);
            return;
        }

        $title       = $data['title'] ?? '';
        $description = $data['description'] ?? '';
        $status      = $data['status'] ?? 'pending';
        $updated_at  = date('Y-m-d H:i:s');

        $db = DatabaseService::getConnection();
        $stmt = $db->prepare("
            UPDATE tasks
            SET title = :title,
                description = :description,
                status = :status,
                updated_at = :updated_at
            WHERE id = :id
        ");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':updated_at', $updated_at);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Task updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to update task']);
        }
    }

    public function deleteTask($id)
    {
        $db = DatabaseService::getConnection();
        $stmt = $db->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Task deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to delete task']);
        }
    }
}
