<?php 

namespace Helpers;

class JsTreeHelper {

	/**
	 * Holds the converted data.
	 * 
	 * @var array
	 */
	private $treeData = [];

	/**
	 * Convert Data to JsTree Format.
	 * 
	 * @param  array  $dataToConvert   Array of Data to converted.  must be sort by the its level or priority
	 * @param  string $parentFieldName Name of the Parent field.
	 * @param  string $displayName     The field name to display in the jstree.
	 * @param  string $primaryKeyName  Primary Key of the Data.
	 * 
	 * @return array                   Converted Array of Data to JsTree Format.
	 */
	public function convertToJsTreeFormat($dataToConvert, $parentFieldName, $displayName, $primaryKeyName)
	{
		if (sizeof($dataToConvert) === 0) return $dataToConvert;

		foreach ($dataToConvert as $row)
		{
			if ( ! $row[$parentFieldName])
			{
				$this->treeData[] = $this->generateFormat($dataToConvert, $parentFieldName, $displayName, $primaryKeyName, $row);
			}
		}

		return $this->treeData;
	}

	/**
	 * Generate the array format.
	 * 
	 * @param  array  $dataToConvert   Array of Data to converted.  must be sort by the its level or priority
	 * @param  string $parentFieldName Name of the Parent field.
	 * @param  string $displayName     The field name to display in the jstree.
	 * @param  string $primaryKeyName  Primary Key of the Data.
	 * @param  array  $rowData         Row of data from the $dataToConvert
	 * 
	 * @return array                   Formatted row of data.
	 */
	private function generateFormat($dataToConvert, $parentFieldName, $displayName, $primaryKeyName, $rowData)
	{
		return [
			"id" => $rowData['id'],
			"text" => $rowData[$displayName],
			"icon"  => (isset($rowData['icon'])) ? $rowData['icon'] : "tree-icon",
			"attr"     => ["class" => "jstree-drop"],
			"metadata" => $rowData,
			"children" => $this->getChildren($dataToConvert, $parentFieldName, $displayName, $rowData[$primaryKeyName], $primaryKeyName)
		];
	}

	/**
	 * Get the children of row data.
	 * 
	 * @param  array  $dataToConvert   Array of Data to converted.  must be sort by the its level or priority
	 * @param  string $parentFieldName Name of the Parent field.
	 * @param  string $displayName     The field name to display in the jstree.
	 * @param  string $primaryKeyName  Primary Key of the Data.
	 * 
	 * @return array                   Children parent data.
	 */
	private function getChildren($dataToConvert, $parentFieldName, $displayName, $primaryKeyValue, $primaryKeyName)
	{
		$childrenData = [];

		foreach ($dataToConvert as $row)
		{
			if ($row[$parentFieldName] === $primaryKeyValue)
			{
				$childrenData[] = $this->generateFormat($dataToConvert, $parentFieldName, $displayName, $primaryKeyName, $row);
			}
		}

		return $childrenData;
	}
}
