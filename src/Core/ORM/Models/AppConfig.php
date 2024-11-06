<?php

/*
 * The MIT License
 *
 * Copyright 2024 szczy.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Neptunium\Core\ORM\Models;

use Neptunium\Core\ORM\Mapping\Column;
use Neptunium\Core\ORM\Mapping\FieldPropertyType;
use Neptunium\Core\ORM\Mapping\Table;
use Neptunium\Core\ORM\Models\Generic\BaseModel;

#[Table(
    name: 'Config',
    comment: 'Tabela ustawień',
    collate: 'utf8mb4_unicode_ci'
)]
class AppConfig extends BaseModel {
    #[Column(
        type: FieldPropertyType::Integer,
        primaryKey: true,
        autoIncrement: true,
        comment: 'ID ustawień'
    )]
    public int $id;

    #[Column(
        type: FieldPropertyType::VarChar,
        length: 30,
        comment: 'Klucz'
    )]
    public string $key = "";
    
    #[Column(
        type: FieldPropertyType::VarChar,
        length: 60,
        comment: 'Wartość'
    )]
    public string $value = "";   

    public function __construct() {
        parent::__construct();
    }
}

