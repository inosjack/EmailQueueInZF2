
###### My Task 

**A post api in zf2(Zend Framework 2) which will accept 2 
params emailId and Name and return success response. 
But I want to send a default email to the entered emailId.
The process of sending email asynchronous.**

* _**create database and create table**_
    ```sql
    DROP TABLE IF EXISTS mailer CASCADE;
    CREATE TABLE mailer (
      id         INT UNSIGNED                          NOT NULL PRIMARY KEY AUTO_INCREMENT,
      queue_name CHAR(16)                              NOT NULL,
      mail       TEXT                                  NOT NULL,
      created    TIMESTAMP DEFAULT now()               NOT NULL,
      status ENUM('waiting', 'processing', 'sent', 'error') NOT NULL DEFAULT 'waiting'
    );
    ```
    
* _**Set Your smtp setting or database setting in config file**_
    ```php
    config/autoload/local.php
    config/autoload/global.php
    config/autoload/modulemailer.php
    ```
    
* _**Php Cli Server**_
    ```php
    php -S 0.0.0.0:8080 -t public/ public/index.php
    ```

* _**Process Queue**_
    ```php
    php public/index.php mailer process EmailQueue
    ```
    
* _**ApiEnd Point:**_
    ```php
     api: http://localhost:8080/api/post
     parameter:
          emailId: "example@example.com"
          name: "example"
    ```
   

