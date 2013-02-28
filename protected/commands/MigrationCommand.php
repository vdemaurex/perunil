<?php

$this->createTable( 'tbl_audit_trail',
			array(
				'id' => 'pk',
				'old_value' => 'text',
				'new_value' => 'text',
				'action' => 'string NOT NULL',
				'model' => 'NOT NULL',
				'field' => 'NOT NULL',
				'stamp' => 'datetime NOT NULL',
				'user_id' => 'string',
				'model_id' => 'string NOT NULL',
			)
		);

		//Index these bad boys for speedy lookups
		$this->createIndex( 'idx_audit_trail_user_id', 'tbl_audit_trail', 'user_id');
		$this->createIndex( 'idx_audit_trail_model_id', 'tbl_audit_trail', 'model_id');
		$this->createIndex( 'idx_audit_trail_model', 'tbl_audit_trail', 'model');
		$this->createIndex( 'idx_audit_trail_field', 'tbl_audit_trail', 'field');
		$this->createIndex( 'idx_audit_trail_old_value', 'tbl_audit_trail', 'old_value');
		$this->createIndex( 'idx_audit_trail_new_value', 'tbl_audit_trail', 'new_value');
		$this->createIndex( 'idx_audit_trail_action', 'tbl_audit_trail', 'action');
?>
