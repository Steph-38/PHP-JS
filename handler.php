<?php

$db = new PDO('mysql:host=localhost;dbname=chat;charset=utf8', 'root', '0000', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$task = "list";

if(array_key_exists("task", $_GET)) {
    $task = $_GET['task'];
}

if ($task == "write") {
    postMessage();
} else {
    getMessages();
}

function getMessages() {
    global $db;
    $results = $db->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 20");
    $messages = $results->fetchAll();
    echo json_encode($messages);
}

function postMessage() {
    global $db;

    if (!array_key_exists("author", $_POST) || !array_key_exists("content", $_POST)) {
        echo json_encode(["status" => "error",
                        "message" => "Un ou plusieurs champs ne sont pas renseigne(s)"
        ]);
        return;
    }

    $author = $_POST['author'];
    $content = $_POST['content'];

    $req = $db->prepare("INSERT INTO messages SET author = :author, content = :content, created_at = NOW()");
    $req->execute([
        "author" => $author,
        "content" => $content
    ]);

    echo json_encode(["status" => "success"]);
}
