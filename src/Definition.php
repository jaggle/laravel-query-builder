<?php

namespace Jjsty1e\LaravelQueryBuilder;

class Definition
{
    /**
     * 完全匹配
     */
    const TERM = 'term';
    /**
     * where in 查询
     */
    const TERMS = 'terms';
    /**
     * 范围查询
     *
     */
    const RANGE = 'range';
    /**
     * 模糊查询 where like '%xxx%'
     */
    const FUZZY = 'fuzzy';
}
