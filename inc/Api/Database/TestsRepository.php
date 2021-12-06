<?php

namespace Testings\Api\Database;

use Testings\Api\Database\BaseDatabase;

class TestsRepository extends BaseDatabase
{
    public function addNewTest()
    {
        $table_name = $this->table_names['TESTS_TABLE'];
        $test_description = $_POST['test_description'];
        $test_name = $_POST['test_name'];

        return $this->wpdb->query(
            $this->wpdb->prepare(
                "
		INSERT INTO $table_name
		( test_name, test_description, is_active, deleted )
		VALUES ( %s, %s, %d, %d )
		",
                $test_name,
                $test_description,
                1,
                0
            )
        );
    }

    public function getTestsList()
    {

        $table_name = $this->table_names['TESTS_TABLE'];

        return $this->wpdb->get_results( "SELECT * FROM $table_name", 'ARRAY_A' );;
    }

	public function editSingleTest( $test_id, $test_name, $test_description, $is_test_active )
	{
		$table_name = $this->table_names['TESTS_TABLE'];

		return $this->wpdb->update($table_name, [ 'test_name' => $test_name, 'test_description' => $test_description, 'is_active' => (int)$is_test_active], [ 'test_id' => (int)$test_id ], ['%s', '%s', '%d'], ['%d']);
	}

    public function removeSingleTest( $test_id )
    {
        $table_name = $this->table_names['TESTS_TABLE'];

        $sql = "DELETE FROM $table_name WHERE `test_id` = %d;";

        return $this->wpdb->query(
            $this->wpdb->prepare(
                $sql, array('test_id' => $test_id)
            )
        );
    }
}