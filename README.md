# laravel-query-builder
A better way to query the database with laravel.

## 示例

以前的代码：

```php
$query = User::query();

if (!empty($params['level'])) {
  query->where('level', $params['level'])
}

// 得到分页参数...

$query->forpage($pageNo, $pageSize)->get();
```

现在的代码：

```php
$query = new QueryBuilder(User::class);
$query->setCondition('level');
$query->query($params);
```
