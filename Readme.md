## Framework doc

1. Prerequis
    - php >= 7.1
    - composer

2. Installation 🚀

    faire un clone du repository
    ```
    git clone <url repository>
    ```

    Installation dependances
    ```
    composer install
    ```

    Configuration
    ```
    cp .env.exemple .env
    ```
    Remplire la configuration dans .env avec la configuration de la base de donnée

    demarer le serveur
    ```
    php -S localhost:8000 -t public/
    ```

    Utilisation docker 🐳
    ```
    docker-compose up
    ```


