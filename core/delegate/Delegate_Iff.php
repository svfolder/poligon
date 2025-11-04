<?php

namespace core\delegate;


use common\components\Dumper;
use core\entities\Wh;

class Delegate_Iff extends Delegate
{


    /**
     * @param $keys
     * @param bool $start_separator
     * @return mixed
     */
    public function createSetByKeys($keys, $start_separator = true)
    {

        $sets = [];
        foreach ($keys as $key => $value) {
            $sets[] = "\n\t\t\t\t`" . $key . "` = '$value'";
        }

        return $sets;

    }

    /**
     * @param $keys
     * @return string
     */
    public function createCSVByKeys($keys)
    {

        $group = [];

        foreach ($keys as $key => $value) {
            $group[] = ' ' . "`{$value}`";
        }

        return join(',', $group);

    }

    /**
     * Создает блок WHERE с логикой СЛОЖЕНИЯ
     * @param mixed $keys
     * @param bool $start_separator
     * @return string|mixed
     */
    public function createWhereByKeys($keys, $start_separator = true)
    {

        $where = '';

        foreach ($keys as $key => $value) {
            $where .= "\n\t\t\t\t" . $this->lib()->iff()->iffWhereAND($value, $key, true, true, '`', 'AND', false);
        }

        if ($start_separator == true) {
            return $where;
        } else {
            return $this->lib()->iff()->iffStartSeparator('AND', $where);
        }

    }


    /**
     * Убирает сепаратор в строке, если он есть в начале текста
     * @param string $separator
     * @param string $string
     * @return string|string[]|null
     */
    public function iffStartSeparator($separator, $string)
    {

        return preg_replace("#^\s*{$separator}#is", "", $string);

    }

    /**
     * @param $str_value
     * @param $field_name
     * @param $field_name_two
     * @param $field_name_three
     * @return string
     */
    static function iffWhereAND_OR($str_value, $field_name, $field_name_two, $field_name_three)
    {

        if (!empty($str_value)) {

            return "AND ( {$field_name} = '{$str_value}' OR {$field_name_two} = '{$str_value}' OR {$field_name_three} = '{$str_value}' ) ";

        } else {
            return '';
        }

    }

    /**
     * @param $str_value
     * @param $field_name
     * @param int $flag_and
     * @return string
     */
    static function iffWhereNotAND($str_value, $field_name, $flag_and = 1)
    {

        if (!empty($str_value)) {

            if ($flag_and) {
                return "AND {$field_name} != '{$str_value}' ";
            } else {
                return "{$field_name} != '{$str_value}' ";
            }

        } else {
            return '';
        }

    }

    /**
     * @param $table
     * @return mixed|string
     */
    private function getLastPart($table)
    {
        $array = explode('_', $table);
        return array_pop($array);
    }

    /**
     * @param $str_value
     * @param $table
     * @param string $join_table
     * @param string $fields
     * @param string $separator
     * @return string
     */
    public function iffJoinAdd($str_value, $table, $join_table = "", $fields = "", $separator = 'JOIN')
    {
        $retval = '';

        if (empty($join_table) || (is_array($fields) == false)) {
            return $table;
        } else {

            $fk = $fields[0];
            $park = $fields[1];

            if (!empty($str_value)) {

                $retval .= "\n\t\t\t\t{$separator} {$join_table} ON {$table}.{$fk} = {$join_table}.{$park}";

            } else {
                return '';
            }

        }


        return $retval;
    }

    /**
     * @param $field_name
     * @param string $field_as
     * @param bool $swap
     * @return string
     */
    public function iffFieldAdd($field_name, $field_as = "", $swap = false)
    {

        $retval = '';

        if ($swap === false) {

            if (!empty($field_as)) {
                $retval = " `{$field_name}` AS `{$field_as}`";
            } else {
                $retval = " `{$field_name}`";
            }

        } elseif ($swap === true) {

            if (!empty($field_as)) {
                $retval = " `{$field_as}` AS `{$field_name}`";
            } else {
                $retval = " `{$field_name}`";
            }
        }

        return $retval;

    }

    /**
     * @param $str_value
     * @param $table
     * @param array $fields
     * @param int $flag
     * @param string $separator
     * @param null $prefix
     * @return string
     */
    public function iffFieldsAdd($str_value, $table, array $fields, $flag = 1, $separator = ',', $prefix = null)
    {

        $retval = '';

        $prefix = $this->getLastPart($table);

        if (!empty($str_value)) {

            foreach ($fields as $field) {

                if ($flag == false) {
                    $retval .= "\n\t\t\t{$table}.`{$field}` as {$prefix}_{$field}";
                } else {
                    $retval .= "\n\t\t\t{$separator}{$table}.`{$field}` as {$prefix}_{$field}";
                }

                $flag = true;
            }

        } else {
            return '';
        }

        return $retval;

    }


    /**
     * Собираем параметры предиката WHERE
     * @param string $str_value
     * @param string $field_name
     * @param int $flag_and
     * @param bool $zero
     * @param string $b
     * @param string $separator
     * @param bool $isnull
     * @return string
     */
    public function iffWhereAND($str_value, $field_name, $flag_and = 1, $zero = false, $b = "`", $separator = "AND", $isnull = false)
    {

        if (($str_value === null) && ($isnull == true)) {
            $apos = '';
            $str_value = 'NULL';
            $sign = 'IS';
        } else {
            $apos = "'";
            $sign = '=';
        }

        if ($zero == true) {

            if ($flag_and) {
                return "{$separator} {$b}{$field_name}{$b} {$sign} {$apos}{$str_value}{$apos} ";
            } else {
                return "{$b}{$field_name}{$b} {$sign} {$apos}{$str_value}{$apos} ";
            }

        }

        if (!empty($str_value)) {

            if ($flag_and) {
                return "\n\t{$separator} {$b}{$field_name}{$b} {$sign} {$apos}{$str_value}{$apos} ";
            } else {
                return "\n\t{$b}{$field_name}{$b} {$sign} {$apos}{$str_value}{$apos} ";
            }

        } else {
            return '';
        }

    }

    /**
     * Создает блок WHERE с логикой ИЛИ
     * @param mixed $keys
     * @param bool $start_separator
     * @return string|mixed
     */
    public function createWhereByKeysEx($keys, $start_separator = true)
    {

        $where = '';

        foreach ($keys as $key => $value) {
            $where .= "\n\t\t\t\t" . $this->lib()->iff()->iffWhere($value);
        }

        if ($start_separator == true) {
            return $where;
        } else {
            return $this->lib()->iff()->iffStartSeparator('AND', $where);
        }

    }


    /**
     * @param Wh $obj
     * @return string
     */
    public function iffWhere(Wh $obj)
    {

        $apos = "'";

        if (!empty($obj->value)) {

            if ($obj->start_operator) {
                return "\n\t{$obj->operator} {$obj->escape}{$obj->field}{$obj->escape} {$obj->sign} {$apos}{$obj->value}{$apos} ";
            } else {
                return "\n\t{$obj->escape}{$obj->field}{$obj->escape} {$obj->sign} {$apos}{$obj->value}{$apos} ";
            }

        } else {
            return '';
        }


    }


    /**
     * @param $str_value
     * @param null $default
     * @param null $suffix
     * @return string
     */
    public function iffPathAddLevel($str_value, $default = null, $suffix = null)
    {

        if (empty($str_value) && (!empty($default)) && is_string($default)) {
            $str_value = $default;
        }

        if (!empty($str_value)) {

            if (!empty($suffix)) {
                return "{$suffix}{$str_value}/";
            } else {
                return $str_value . '/';
            }

        } else {
            return '';
        }

    }

    /**
     * @param array $fields
     * @return string
     */
    public function iffGroupAdd(array $fields)
    {

        $fields = $this->clearArrayEmpty($fields);

        if (sizeof($fields) > 0) {
            return "GROUP BY " . implode(', ', $fields);
        } else {
            return '';
        }

    }

    /**
     * @param $start
     * @param $end
     * @param null $default
     * @return string
     */
    public function iffLimitAdd($start, $end, $default = null)
    {

        if (($start >= 0) && ($end > 0)) {

            return "LIMIT {$start}, {$end} ";

        } else {

            if (!empty($default) && ($default > 0)) {
                return "LIMIT 0, {$default} ";
            } else {
                return "";
            }

        }


    }

    /**
     * @param $str_value
     * @return string
     */
    public function iffOrderAdd($str_value)
    {

        if (!empty($str_value)) {
            return "ORDER BY {$str_value}";
        } else {
            return '';
        }

    }

    /**
     * @param $value
     * @param null $template
     * @param null $default
     * @return string
     */
    public function iffTemplateAdd($value, $template = null, $default = null)
    {

        if (empty($value) && (!empty($default)) && is_string($default)) {
            return $default;
        }

        if (!empty($value)) {

            return $template;

        } else {
            return '';
        }

    }

    /**
     * @param $array
     * @return array
     */
    public function clearArrayEmpty($array)
    {

        $ret_arr = [];

        foreach ($array as $val) {
            if (!empty($val)) {
                $ret_arr[] = trim($val);
            }
        }

        return $ret_arr;
    }

    /**
     * @param $array
     * @return mixed
     */
    public function clearAssoccArrayEmpty($array)
    {

        foreach ($array as $key => $value) {
            if (empty($value)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * @param $array
     * @param $form_name
     * @return array|bool
     */
    public function arrayJQSerializeToAssocc($array, $form_name)
    {

        $assoc_data = [];

        if (is_array($array) && sizeof($array) > 0) {

            foreach ($array as $item) {

                $key = str_replace([$form_name, '[', ']'], '', $item['name']);
                $value = $item['value'];
                $assoc_data[$key] = $value;

            }

            return $assoc_data;

        } else {
            return false;
        }


    }


    /**
     * @param $key
     * @param $value
     * @return string
     */
    public function getWhereGroupKey($key, $value)
    {

        if (empty($value)) {
            return '';
        } else {

            if (strpos($key, '_') !== false) {
                $exp = explode('_', $key);
                $key = $exp[0];
            }

            return "({$key},{$value})";
        }
    }

    /**
     * @param int $day
     * @return int
     */
    public function iffMaxDay(int $day)
    {
        if ($day > 31){
            return 31;
        }else{
            return $day;
        }
    }

    /**
     * @param int $month
     * @return int
     */
    public function iffMaxMonth(int $month)
    {
        if ($month > 12){
            return 12;
        }else{
            return $month;
        }
    }

    /**
     * @param int $year
     * @return int
     */
    public function iffMaxYear(int $year)
    {
        $current_year = (int)date('Y', time());

        if ($year > $current_year){
            return $current_year;
        }else{
            return $year;
        }
    }

}

?>