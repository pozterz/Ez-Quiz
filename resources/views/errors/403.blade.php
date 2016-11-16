<!DOCTYPE html>
<html>
    <head>
        <title>403-Forbidden.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
				@include('layouts.head')
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="notification is-danger">
                <div class="title">ไม่สามารถเข้าถึงข้อมูลนี้ได้</div>
                <div class="subtitle">เนื่องจากคุณไม่ได้รับสิทธิ์ในการเข้าถึงข้อมูล</div>
                
            </div>
            <a class="button is-danger is-medium is-outlined" href="{{ url('index') }}">พาฉันกลับไป</a>
        </div>
    </body>
</html>
