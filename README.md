## 📦 Установка

<details>
<summary><b>composer.json</b> (нажмите, чтобы развернуть)</summary>

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Integrat\\Amocrm\\": "vendor/integrat/amocrm-library/src/Integrat/Amocrm/"
        }
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/AndreyB66/amocrm-library.git"
        }
    ],
    "require": {
        "integrat/amocrm-library": "*"
    },
    "config": {
        "secure-http": false
    }
}
```

</details>

Или выполните пошагово:

**1. Добавьте репозиторий в `composer.json`:**
```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Integrat\\Amocrm\\": "vendor/integrat/amocrm-library/src/Integrat/Amocrm/"
        }
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/AndreyB66/amocrm-library.git"
        }
    ],
    "require": {
        "integrat/amocrm-library": "*"
    },
    "config": {
        "secure-http": false
    }
}
```
