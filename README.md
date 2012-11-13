Tips:
 - Para efetuar checkout do repositório remoto, executar "git pull" no terminal.
 - Após efetuar o commit na interface IDE, executar "git push origin master" no terminal.
 - Para resetar o sistema afim de mudar para o ambiente de desenvolvimento utilizar os seguintes comandos:
        - php app/console cache:clear --env=prod --no-debug
        - php app/console assets:install web_directory
        - php app/console assetic:dump web_directory
 - Para alterar host do GIT utilizar git remote set-url origin <URL>

fenix-sistema

