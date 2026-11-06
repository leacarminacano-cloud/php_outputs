<?php
// CONTROLLER: handles requests, validates input, calls the model, picks a view.
require_once __DIR__ . "/../models/Faculty.php";

class FacultyController
{
    private Faculty $model;

    public function __construct()
    {
        $this->model = new Faculty();
    }

    public function index(): void
    {
        $search  = trim($_GET["q"] ?? "");
        $records = $this->model->all($search);
        $flash   = $this->pullFlash();
        $this->render("faculty/index", compact("records", "search", "flash"));
    }

    public function create(): void
    {
        $data = $this->blank();
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = $this->collect();
            $errors = $this->validate($data);
            if (!$errors) {
                $this->model->create($data);
                $this->redirect("Faculty record added successfully!");
            }
        }
        $this->render("faculty/form", ["data" => $data, "errors" => $errors, "title" => "Add Faculty", "id" => null]);
    }

    public function edit(): void
    {
        $id  = (int)($_GET["id"] ?? 0);
        $row = $this->model->find($id);
        if (!$row) { $this->redirect("Record not found."); }

        $data = $row;
        $errors = [];
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = $this->collect();
            $errors = $this->validate($data);
            if (!$errors) {
                $this->model->update($id, $data);
                $this->redirect("Faculty record updated successfully!");
            }
        }
        $this->render("faculty/form", ["data" => $data, "errors" => $errors, "title" => "Edit Faculty", "id" => $id]);
    }

    public function delete(): void
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->model->delete((int)($_POST["id"] ?? 0));
            $this->redirect("Faculty record deleted.");
        }
        $this->redirect();
    }

    // ---------- helpers ----------
    private function blank(): array
    {
        return ["first_name"=>"","middle_name"=>"","last_name"=>"","age"=>"","gender"=>"","address"=>"","position"=>"","salary"=>""];
    }

    private function collect(): array
    {
        $d = [];
        foreach ($this->blank() as $k => $_) { $d[$k] = trim($_POST[$k] ?? ""); }
        return $d;
    }

    private function validate(array $d): array
    {
        $e = [];
        if (!preg_match("/^[A-Za-z\\s.\x27-]{2,50}$/", $d["first_name"])) $e["first_name"] = "First name is required (letters only).";
        if ($d["middle_name"] !== "" && !preg_match("/^[A-Za-z\\s.\x27-]{1,50}$/", $d["middle_name"])) $e["middle_name"] = "Letters only.";
        if (!preg_match("/^[A-Za-z\\s.\x27-]{2,50}$/", $d["last_name"])) $e["last_name"] = "Last name is required (letters only).";
        if (!ctype_digit($d["age"]) || (int)$d["age"] < 18 || (int)$d["age"] > 100) $e["age"] = "Age must be between 18 and 100.";
        if (!in_array($d["gender"], ["Male","Female","Other"], true)) $e["gender"] = "Select a gender.";
        if (strlen($d["address"]) < 5) $e["address"] = "Address is required.";
        if (strlen($d["position"]) < 2) $e["position"] = "Position is required.";
        if (!is_numeric($d["salary"]) || (float)$d["salary"] <= 0 || (float)$d["salary"] > 99999999.99) $e["salary"] = "Enter a valid salary greater than 0.";
        return $e;
    }

    private function render(string $view, array $vars = []): void
    {
        extract($vars);
        require __DIR__ . "/../views/layout/header.php";
        require __DIR__ . "/../views/$view.php";
        require __DIR__ . "/../views/layout/footer.php";
    }

    private function redirect(string $message = ""): void
    {
        if ($message !== "") { $_SESSION["flash"] = $message; }
        header("Location: index.php");
        exit;
    }

    private function pullFlash(): string
    {
        $m = $_SESSION["flash"] ?? "";
        unset($_SESSION["flash"]);
        return $m;
    }
}
