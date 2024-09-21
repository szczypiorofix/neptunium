<?php

namespace Neptunium\Core\ORM\Mapping;

enum FieldPropertyType {
    case VarChar;
    case Integer;
    case BigInteger;
    case Float;
    case Double;
    case Decimal;
    case DateTime;
    case Timestamp;
    case Time;
    case Year;
    case Boolean;

    public function label(): string {
        return FieldPropertyType::getLabel($this);
    }

    public static function getLabel(self $value): string {
        return match ($value) {
            FieldPropertyType::VarChar      => 'VARCHAR',
            FieldPropertyType::Integer      => 'INT',
            FieldPropertyType::BigInteger   => 'BIGINT',
            FieldPropertyType::Float        => 'FLOAT',
            FieldPropertyType::Double       => 'DOUBLE',
            FieldPropertyType::Decimal      => 'DECIMAL',
            FieldPropertyType::DateTime     => 'DATETIME',
            FieldPropertyType::Timestamp    => 'TIMESTAMP',
            FieldPropertyType::Time         => 'TIME',
            FieldPropertyType::Year         => 'YEAR',
            FieldPropertyType::Boolean      => 'BOOL',
        };
    }
}
