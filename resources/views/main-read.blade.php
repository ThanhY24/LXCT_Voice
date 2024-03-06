<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
            rel="stylesheet"
        />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
        <title>Danh sách học viên chuẩn bị vào thi</title>
    </head>
    <body>
        <div class="main">
            <div class="list-right" style="width:100%">
                <div class="card card-student" style="margin-bottom: 20px">
                    <p class="card-header" style="height: 40px">
                        Thông Tin
                    </p>
                    <div class="card-body" style="background-color: unset">
                        <div class="info-student">
                            <div class="info-student-right">
                                <div class="info-student-row">
                                    <span id="student-voice-text"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <p class="card-header" style="height: 40px">
                        Danh Sách Học Viên
                    <div
                        class="card-body"
                        style="
                            max-height: 500px;
                            overflow: auto;
                            padding: 0;
                            margin: 0;
                        "
                    >
                        <table class="table1" id="student-table">
                            <tr style="height: 30px">
                                <th
                                    style="
                                        position: sticky;
                                        top: 0;
                                        background-color: #f1d96e;
                                    "
                                >
                                    STT
                                </th>
                                <th
                                    style="
                                        position: sticky;
                                        top: 0;
                                        background-color: #f1d96e;
                                    "
                                >
                                    Số báo danh
                                </th>
                                <th
                                    style="
                                        position: sticky;
                                        top: 0;
                                        background-color: #f1d96e;
                                    "
                                >
                                    Tên học viên
                                </th>
                                
                                <th
                                    style="
                                        position: sticky;
                                        top: 0;
                                        background-color: #f1d96e;
                                    "
                                >
                                    Trạng Thái
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <audio controls id="voice1Audio" >
            <source src="" type="audio/mp3" />
        </audio>
        <audio controls id="voice2Audio" >
            <source src="" type="audio/mp3" />
        </audio>
        <script src="{{asset('js/readTemp.js')}}"></script>
    </body>
</html>
