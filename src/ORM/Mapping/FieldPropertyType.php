<?php

namespace Neptunium\ORM\Mapping;

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
}
