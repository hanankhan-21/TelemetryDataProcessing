<?php

namespace Helpers;

use Psr\Container\ContainerInterface;
use PDO;
use PDOException;
use Helpers\SQLQueries;
use Exception;

class Database
{
    private string $host;
    private string $pass;
    private string $dbName;
    private string $user;
    private string $port;
    private string $charset;
    private ?PDO $db = null;

    private array $errors = [
        'db_error'  => false,
        'sql_error' => null,
    ];

    private $prepared_statement;

    public function __construct(ContainerInterface $container)
    {
        $dbSettings     = $container->get('settings')['db'];
        $this->host     = $dbSettings['host'];
        $this->dbName   = $dbSettings['dbname'];
        $this->user     = $dbSettings['user'];
        $this->pass     = $dbSettings['pass'];
        $this->charset  = $dbSettings['charset'];
        $this->port     = $dbSettings['port'];
    }

    public function __destruct()
    {
        $this->db = null;
    }

    public function connectToDatabase(): PDO
    {
        if ($this->db instanceof PDO) {
            return $this->db;
        }

        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset={$this->charset}";

        try {
            $this->db = new PDO($dsn, $this->user, $this->pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // In production, log instead of die()
            die('Database connection failed: ' . $e->getMessage());
        }

        return $this->db;
    }

    public function testConnection(): void
    {
        try {
            $this->connectToDatabase();
            echo "Database connection successful!";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * safeQuery
     *
     * - uses prepared statements (prevents SQL injection)
     * - For SELECT: returns array of rows ( [] if none )
     * - For INSERT/UPDATE/DELETE: returns true on success
     * - On error: returns false and sets $this->errors
     */
    public function safeQuery(string $query, array $params = []): mixed
    {
        $this->errors = ['db_error' => false, 'sql_error' => null];

        try {
            $pdo  = $this->connectToDatabase();
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            // Detect query type (first word of query)
            $queryType = strtoupper(strtok(trim($query), ' '));

            if ($queryType === 'SELECT') {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $rows ?: [];
            }

            // For non-SELECT (INSERT/UPDATE/DELETE), just say success
            return true;

        } catch (PDOException $e) {
            $this->errors['db_error']  = true;
            $this->errors['sql_error'] = $e->getMessage();
            return false;
        }
    }

    public function getLastError(): ?string
    {
        return $this->errors['sql_error'] ?? null;
    }

    /**
     * Insert a new user
     * Returns: true on success, false on DB error
     */
    public function addUser($validated_fullName, $validated_email, $validated_phoneNumber, $hashed_password): bool
    {
        try {
            $sql_query = SQLQueries::addNewUser();
            $queryParameters = [
                ':fullName'    => $validated_fullName,
                ':email'       => $validated_email,
                ':phoneNumber' => $validated_phoneNumber,
                ':password'    => $hashed_password,
            ];

            $result = $this->safeQuery($sql_query, $queryParameters);

        } catch (Exception $e) {
            $this->errors['db_error']  = true;
            $this->errors['sql_error'] = $e->getMessage();
            return false;
        }

        // For INSERT, safeQuery returns true on success, false on error
        return $result === true;
    }

    /**
     * Check if user exists by email
     * Returns: true if user found, false if not found or DB error
     */
    public function userExists(string $validatedEmail): bool
    {
        try {
            $sqlQuery = SQLQueries::returnUserDetails();
            $params   = [':email' => $validatedEmail];

            $rows = $this->safeQuery($sqlQuery, $params);

            if ($rows === false) {
                // DB error
                return false;
            }

            return !empty($rows);

        } catch (Exception $e) {
            $this->errors['db_error']  = true;
            $this->errors['sql_error'] = $e->getMessage();
            return false;
        }
    }

    /**
     * Retrieve a single user row by email
     * Returns: array (user row) on success, false if not found or DB error
     */
    public function retrieveUser(string $validatedEmail): array|false
    {
        try {
            $sqlQuery = SQLQueries::returnUserDetails();
            $params   = [':email' => $validatedEmail];

            $rows = $this->safeQuery($sqlQuery, $params);

            if ($rows === false || empty($rows)) {
                // DB error OR no user found
                return false;
            }

            // return first row (email is unique)
            return $rows[0];

        } catch (Exception $e) {
            $this->errors['db_error']  = true;
            $this->errors['sql_error'] = $e->getMessage();
            return false;
        }
    }
}

?>
