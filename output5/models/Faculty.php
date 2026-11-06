<?php
// MODEL: talks to the database only. No HTML here.
require_once __DIR__ . "/../config/database.php";

class Faculty
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getConnection();
    }

    public function all(string $search = ""): array
    {
        if ($search !== "") {
            $stmt = $this->db->prepare(
                "SELECT * FROM faculty WHERE first_name LIKE ? OR last_name LIKE ? OR position LIKE ? ORDER BY id DESC"
            );
            $stmt->execute(array_fill(0, 3, "%$search%"));
        } else {
            $stmt = $this->db->query("SELECT * FROM faculty ORDER BY id DESC");
        }
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM faculty WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO faculty (first_name, middle_name, last_name, age, gender, address, position, salary)
             VALUES (?,?,?,?,?,?,?,?)"
        );
        $stmt->execute($this->values($d));
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->db->prepare(
            "UPDATE faculty SET first_name=?, middle_name=?, last_name=?, age=?, gender=?, address=?, position=?, salary=?
             WHERE id = ?"
        );
        $stmt->execute([...$this->values($d), $id]);
    }

    public function delete(int $id): void
    {
        $this->db->prepare("DELETE FROM faculty WHERE id = ?")->execute([$id]);
    }

    private function values(array $d): array
    {
        return [
            $d["first_name"], $d["middle_name"] !== "" ? $d["middle_name"] : null, $d["last_name"],
            (int)$d["age"], $d["gender"], $d["address"], $d["position"], (float)$d["salary"],
        ];
    }
}
