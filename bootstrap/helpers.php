
 <?php
 /*
      usually we config our database in config/database.php, so we need build a help file to provide configuration data for config/database.php,
      this help file will help us assign different configeraton  data for our database connection according to the environment.
      in our project, for local environment, we use MySql.
      For Heroku environment, we use PostgreSql, in heroku's configeration, we have already created and set IS_IN_HEROKU=ture
      We modified config/database.php, so that the default db connection will be adjusted according to our environment
      check the default and pgsql part
  */
   function get_db_config(){
       if(getenv('IS_IN_HEROKU')){
           $url = parse_url(getenv("DATABASE_URL"));

           return $db_config = [
               'connection' => 'pgsql',
               'host' => $url["host"],
               'database' => substr($url["path"], 1),
               'username' => $url["user"],
               'password' => $url["pass"],
           ];
       }else{
           return $db_config = [
               'connection' => env('DB_CONNECTION', 'sql'),
               'host' => env('DB_HOST', 'localhost'),
               'database'  => env('DB_DATABASE', 'forge'),
               'username'  => env('DB_USERNAME', 'forge'),
               'password'  => env('DB_PASSWORD', ''),
           ];
       }
   }

   /*
   可以看到，我们定义了 get_db_config 方法来根据数据库的不同运行环境获取不同的配置信息。
   通过 Heroku 生成的 DATABASE_URL 包含了一切与数据库相关的配置信息，如主机、用户名、密码、数据库等，
   因此我们需要使用 parse_url 方法对其进行解析，来获取到指定的值。
   当运行环境为 Heroku 时，我们使用 DATABASE_URL 提供的数据库配置信息，如果为其它环境，则使用默认的数据库配置信息。

    在我们新增 helpers.php 文件之后，还需要在应用中对该文件进行配置，自动加载该文件。(bootstrap/autoload.php)
   */
  ?>
