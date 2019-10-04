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

3. API
    > Inscription d'un nouvel utilisateur
    ```
    POST /user/register
    ```
    | Paramètre   |     Type    |        Description |
    | :------------: | :-------------: | :-------------: |
    | ```username```       |     ```string```     |       **Requis.** Pseudo de l'utilisateur |
    | ```email```       |     ```string```     |       **Requis.** Adresse email de l'utilisateur |
    | ```pass```       |     ```string```     |       **Requis.** Mot de passe de l'utilisateur |
    ---
    > Authentification d'utilisateur
    ```
    POST /user/login
    ```
    | Paramètre   |     Type    |        Description |
    | :------------: | :-------------: | :-------------: |
    | ```email```       |     ```string```     |       **Requis.** Adresse email de l'utilisateur |
    | ```pass```       |     ```string```     |       **Requis.** Mot de passe de l'utilisateur |
    ---
    > Liste des discussion d'un utilisateur
    ```
    GET /chat/discussion
    ```
    | Paramètre   |     Type    |        Description |
    | :------------: | :-------------: | :-------------: |
    | ```Authorization```       |     ```string```     |      **Header.** **Requis.** Token jwt |
    ---
    > Liste des messages d'une discussion
    ```
    GET /chat/discussion/{id}
    ```
    | Paramètre   |     Type    |        Description |
    | :------------: | :-------------: | :-------------: |
    | ```Authorization```       |     ```string```     |      **Header.** **Requis.** Token jwt |