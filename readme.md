# Ez - Quiz | Online quiz

Ez - Quiz is a online quiz system for student and lecturer and its our group work in Web Engineering & Apllication course.

Ez -Quiz เป็นระบบทำควิซออนไลน์สำหรับนักเรียนและอาจารย์ และยังเป็นส่วนหนึ่งในวิชา Web Engineering & Application.

# Member

|  ชือ   | รหัสนักศึกษา   	|    ตำแหน่ง   |
|---	                         |---            |---            |
|   นาย ธราเทพ หนูเหมือน        |   5635512083   |  Programmer   |
|   นาย วรวิชญ์ มาแดง           |   5635512086    | Co-Programmer |
|   นาย วัชรพงษ์ จุ้ยนคร          |    5635512089  | Tester          |
|   นาย อลงกรณ์ สุวรรณจันทร์     |  5635512094     | Designer      |
|   นางสาว วรรณวิมล สุรพันธ์      |  5635512097    | Designer      |
|   นางสาว พิชชาพร สินธพานนท์  |    5635512107  |   Designer    |


# Features
  - Authentication & Authorization.
  - Register subjects for student and do any quizzes in registered subjects.
  - Create Subject for Lecturer and make quizzes.

# ฟีเจอร์
  - ระบบยืนยันตัวตนและตรวจสอบความเป็นเจ้าของ
  - นักเรียนสามารถสมัครเข้ารายวิชาและสามารถทำควิซใด ๆ ได้ภายในวิชาที่ได้สมัครไว้แล้ว
  - อาจารย์สามารถสร้างวิชา และสร้างแบบทดสอบได้.


### Tech

Ez - Quiz uses a number of open source projects to work properly.

Ez - Quiz ใช้ open source project ในการทำงานร่วมกันดังนี้.

* [AngularJS] - HTML enhanced for web apps!
* [bulma] - a modern CSS framework based on Flexbox.
* [Gulp] - the streaming build system
* [Laravel] - The PHP Framework For Web Artisans
* [jQuery] - duh

And of course Ez - Quiz itself is open source with a [public repository][ezquiz]
 on GitHub.
 
แน่นอนว่า Ez - Quiz เป็น open source เช่นกันที่ [public directory][ezquiz]
 on GitHub.

### Installation

Ez-Quiz requires [Laravel](https://laravel.com/docs/5.2/) v5.2+ to run.

Install with composer.
สามารถใช้ composer ในการติดตั้ง.

```sh
$ composer install
```

For production environments.
ตั้งค่า environment สำหรับ production.

> rename .env.example to .env and edit.
> เปลี่ยนชื่อไฟล์ .env.example เป็น .env และแก้ไข.
> .env will protected with .htaccess.
> .env จะถูกปกป้องด้วย .htaccess.

```sh
APP_ENV=production
APP_DEBUG=false
DB_HOST=localhost
DB_DATABASE= [YOUR Database name]
DB_USERNAME= [YOUR Database password]
DB_PASSWORD = [YOUR Database password]
```

deploy key & database.
การสร้างคีย์และตารางในฐานข้อมูล.
```sh
php artisan key:generate
php artisan migrate
```


   [ezquiz]: <https://github.com/joemccann/dillinger>
   [git-repo-url]: <https://github.com/joemccann/dillinger.git>
   [Laravel]: <https://laravel.com/docs/5.2/>
   [@thomasfuchs]: <http://twitter.com/thomasfuchs>
   [Bulma]: <http://bulma.io/>
   [jQuery]: <http://jquery.com>
   [AngularJS]: <http://angularjs.org>
   [Gulp]: <http://gulpjs.com>

   [PlDb]: <https://github.com/joemccann/dillinger/tree/master/plugins/dropbox/README.md>
   [PlGh]:  <https://github.com/joemccann/dillinger/tree/master/plugins/github/README.md>
   [PlGd]: <https://github.com/joemccann/dillinger/tree/master/plugins/googledrive/README.md>
   [PlOd]: <https://github.com/joemccann/dillinger/tree/master/plugins/onedrive/README.md>
