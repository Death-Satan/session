# cookie和session的简单封装

# 安装

---
```shell
composer require death_satan/session -vvv
```
---

# 使用

---
```php
$session = new \DeathSatan\Session\Session();
$session->set('aaa','bbb');//设置一个session
$session->delete('aaa');//删除session

$cookie = new \DeathSatan\Session\Cookie();

$cookie->set('aaa','bbb');//设置一个cookie
```
---

