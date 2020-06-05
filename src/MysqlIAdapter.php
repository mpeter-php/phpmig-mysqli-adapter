<?php
/**
 *
 */

namespace Phpmig\Adapter;

use Phpmig\Migration\Migration;

/**
 * MysqlIAdapter
 */
class MysqlIAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var mysqli
     */
    protected $connection;

    /**
     * Constructor
     *
     * @param mysqli $connection
     * @param string $tableName
     */
    public function __construct(mysqli $connection, $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    /**
     * Get all migrated version numbers
     *
     * @return array
     */
    public function fetchAll()
    {
        $res = $this->connection->query(
            sprintf(
                "SELECT `version` FROM `%s` ORDER BY `version`",
                $this->connection->real_escape_string($this->tableName)
            )
        );
        $versions = [];
        while (list($version) = $res->fetch_row()) {
            $versions[] = $version;
        }
        return $versions;
    }

    /**
     * Migrate up
     *
     * @param Migration $migration
     *
     * @return AdapterInterface
     */
    public function up(Migration $migration)
    {
        $this->connection->query(
            vsprintf(
                "INSERT INTO `%s` SET `version` = '%s'",
                [
                    $this->connection->real_escape_string($this->tableName),
                    $this->connection->real_escape_string($migration->getVersion())
                ]
            )
        );

        return $this;
    }

    /**
     * Migrate down
     *
     * @param Migration $migration
     *
     * @return AdapterInterface
     */
    public function down(Migration $migration)
    {
        $this->connection->query(
            vsprintf(
                "DELETE FROM `%s` WHERE `version` = '%s'",
                [
                    $this->connection->real_escape_string($this->tableName),
                    $this->connection->real_escape_string($migration->getVersion())
                ]
            )
        );

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSchema()
    {
        $res = $this->connection->query(
            sprintf("SHOW TABLES LIKE '%s'", $this->connection->real_escape_string($this->tableName))
        );
        return $res->num_rows > 0;
    }

    /**
     * @return AdapterInterface
     */
    public function createSchema()
    {
        $this->connection->query(
            sprintf(
                "CREATE TABLE `%s` (`version` VARCHAR(255) NOT NULL)",
                $this->connection->real_escape_string($this->tableName)
            )
        );
        return $this;
    }
}
