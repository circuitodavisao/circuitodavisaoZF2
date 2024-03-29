# Zend\\Db\\Metadata

`Zend\Db\Metadata` is as sub-component of Zend\\Db that makes it possible to get metadata
information about tables, columns, constraints, triggers, and other information from a database in a
standardized way. The primary interface for the Metadata objects is:

```php
interface MetadataInterface
{
    public function getSchemas();

    public function getTableNames($schema = null, $includeViews = false);
    public function getTables($schema = null, $includeViews = false);
    public function getTable($tableName, $schema = null);

    public function getViewNames($schema = null);
    public function getViews($schema = null);
    public function getView($viewName, $schema = null);

    public function getColumnNames($table, $schema = null);
    public function getColumns($table, $schema = null);
    public function getColumn($columnName, $table, $schema = null);

    public function getConstraints($table, $schema = null);
    public function getConstraint($constraintName, $table, $schema = null);
    public function getConstraintKeys($constraint, $table, $schema = null);

    public function getTriggerNames($schema = null);
    public function getTriggers($schema = null);
    public function getTrigger($triggerName, $schema = null);
}
```

## Basic Usage

Usage of `Zend\Db\Metadata` is very straight forward. The top level class
Zend\\Db\\Metadata\\Metadata will, given an adapter, choose the best strategy (based on the database
platform being used) for retrieving metadata. In most cases, information will come from querying the
INFORMATION\_SCHEMA tables generally accessible to all database connections about the currently
accessible schema.

Metadata::get\*Names() methods will return an array of strings, while the other methods will return
specific value objects with the containing information. This is best demonstrated by the script
below.

```php
$metadata = new Zend\Db\Metadata\Metadata($adapter);

// get the table names
$tableNames = $metadata->getTableNames();

foreach ($tableNames as $tableName) {
    echo 'In Table ' . $tableName . PHP_EOL;

    $table = $metadata->getTable($tableName);


    echo '    With columns: ' . PHP_EOL;
    foreach ($table->getColumns() as $column) {
        echo '        ' . $column->getName()
            . ' -> ' . $column->getDataType()
            . PHP_EOL;
    }

    echo PHP_EOL;
    echo '    With constraints: ' . PHP_EOL;

    foreach ($metadata->getConstraints($tableName) as $constraint) {
        /** @var $constraint Zend\Db\Metadata\Object\ConstraintObject */
        echo '        ' . $constraint->getName()
            . ' -> ' . $constraint->getType()
            . PHP_EOL;
        if (!$constraint->hasColumns()) {
            continue;
        }
        echo '            column: ' . implode(', ', $constraint->getColumns());
        if ($constraint->isForeignKey()) {
            $fkCols = array();
            foreach ($constraint->getReferencedColumns() as $refColumn) {
                $fkCols[] = $constraint->getReferencedTableName() . '.' . $refColumn;
            }
            echo ' => ' . implode(', ', $fkCols);
        }
        echo PHP_EOL;

    }

    echo '----' . PHP_EOL;
}
```

Metadata returns value objects that provide an interface to help developers better explore the
metadata. Below is the API for the various value objects:

The TableObject:

```php
class Zend\Db\Metadata\Object\TableObject
{
    public function __construct($name);
    public function setColumns(array $columns);
    public function getColumns();
    public function setConstraints($constraints);
    public function getConstraints();
    public function setName($name);
    public function getName();
}
```

The ColumnObject:

```php
class Zend\Db\Metadata\Object\ColumnObject {
    public function __construct($name, $tableName, $schemaName = null);
    public function setName($name);
    public function getName();
    public function getTableName();
    public function setTableName($tableName);
    public function setSchemaName($schemaName);
    public function getSchemaName();
    public function getOrdinalPosition();
    public function setOrdinalPosition($ordinalPosition);
    public function getColumnDefault();
    public function setColumnDefault($columnDefault);
    public function getIsNullable();
    public function setIsNullable($isNullable);
    public function isNullable();
    public function getDataType();
    public function setDataType($dataType);
    public function getCharacterMaximumLength();
    public function setCharacterMaximumLength($characterMaximumLength);
    public function getCharacterOctetLength();
    public function setCharacterOctetLength($characterOctetLength);
    public function getNumericPrecision();
    public function setNumericPrecision($numericPrecision);
    public function getNumericScale();
    public function setNumericScale($numericScale);
    public function getNumericUnsigned();
    public function setNumericUnsigned($numericUnsigned);
    public function isNumericUnsigned();
    public function getErratas();
    public function setErratas(array $erratas);
    public function getErrata($errataName);
    public function setErrata($errataName, $errataValue);
}
```

The ConstraintObject:

```php
class Zend\Db\Metadata\Object\ConstraintObject
{
    public function __construct($name, $tableName, $schemaName = null);
    public function setName($name);
    public function getName();
    public function setSchemaName($schemaName);
    public function getSchemaName();
    public function getTableName();
    public function setTableName($tableName);
    public function setType($type);
    public function getType();
    public function hasColumns();
    public function getColumns();
    public function setColumns(array $columns);
    public function getReferencedTableSchema();
    public function setReferencedTableSchema($referencedTableSchema);
    public function getReferencedTableName();
    public function setReferencedTableName($referencedTableName);
    public function getReferencedColumns();
    public function setReferencedColumns(array $referencedColumns);
    public function getMatchOption();
    public function setMatchOption($matchOption);
    public function getUpdateRule();
    public function setUpdateRule($updateRule);
    public function getDeleteRule();
    public function setDeleteRule($deleteRule);
    public function getCheckClause();
    public function setCheckClause($checkClause);
    public function isPrimaryKey();
    public function isUnique();
    public function isForeignKey();
    public function isCheck();

}
```

The TriggerObject:

```php
class Zend\Db\Metadata\Object\TriggerObject
{
    public function getName();
    public function setName($name);
    public function getEventManipulation();
    public function setEventManipulation($eventManipulation);
    public function getEventObjectCatalog();
    public function setEventObjectCatalog($eventObjectCatalog);
    public function getEventObjectSchema();
    public function setEventObjectSchema($eventObjectSchema);
    public function getEventObjectTable();
    public function setEventObjectTable($eventObjectTable);
    public function getActionOrder();
    public function setActionOrder($actionOrder);
    public function getActionCondition();
    public function setActionCondition($actionCondition);
    public function getActionStatement();
    public function setActionStatement($actionStatement);
    public function getActionOrientation();
    public function setActionOrientation($actionOrientation);
    public function getActionTiming();
    public function setActionTiming($actionTiming);
    public function getActionReferenceOldTable();
    public function setActionReferenceOldTable($actionReferenceOldTable);
    public function getActionReferenceNewTable();
    public function setActionReferenceNewTable($actionReferenceNewTable);
    public function getActionReferenceOldRow();
    public function setActionReferenceOldRow($actionReferenceOldRow);
    public function getActionReferenceNewRow();
    public function setActionReferenceNewRow($actionReferenceNewRow);
    public function getCreated();
    public function setCreated($created);
}
```
