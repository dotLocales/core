<?php
class dL_DB
{
	const DB_A_L_PDO = 0;
	const DB_A_L_MDB2 = 1;
	
	const DB_DEFAULT_TYPE = 'sqlite';
	
	static private $_connection;
	static private $_type;
	static private $_db_abstraction_layer;
	
	public static function connect($db_abstraction_layer = null)
	{
		if(is_null(self::$connection) !== false)
		{
			return true;
		}
		
		if(is_null($db_abstraction_layer))
		{
			$db_abstraction_layer = self::_getDBAbstrationLayer();
		}
		
		$success = (self::DB_A_L_PDO == $db_abstraction_layer)
			? sefl::_connectPDO()
			: sefl::_connectMDB2();
		
		return $success;
	}
	
	public static function getType()
	{
		return is_null(self::$_type)
				? self::$_type
				: dL_Config::getValue('database', 'type', self::DB_DEFAULT_TYPE);
	}
	
	private static function _getDBAbstrationLayer()
	{
		if(class_exists('PDO') && defined('INSTALL') !== false)
		{
			$db_type = dL_Config::getValue('database', 'type', self::DB_DEFAULT_TYPE);
			
			if('oci' == $db_type) return self::DB_A_L_MDB2;
			if('sqlite3' == $db_type) $db_type = 'sqlite';
			
			if(array_search($db_type, PDO::getAvailableDrivers()))
			{
				return self::DB_A_L_PDO;
			}
		}
		return self::DB_A_L_MDB2;
	}
	
	
}