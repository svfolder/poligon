<?php

namespace core\helpers;

class RelationHelper
{
    /**
     * @param string $table1
     * @param string $table2
     * @return string
     */
    public static function generateCrossTableName(string $table1, string $table2): string
    {
        $tables = [$table1, $table2];
        sort($tables);
        return $tables[0] . '_' . $tables[1] . '_assignment';
    }
}