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
        <title>Đọc tên</title>
    </head>
    <body  onkeydown="keyPress(event)">
        <div class="main">
            <div class="list-left">
                @if(session('message'))
                <div class="msg">{{ session('message') }}</div>
                @endif
                <div class="msg" id="msg"></div>
                <div class="card" style="display: none">
                    <p class="card-header">Thông tin khóa thi</p>
                    <div
                        class="card-body"
                        
                    >
                        <div class="list-course-row">
                            <label for="">Khóa thi</label>
                            <select name="idExaminations" id="idExaminations">
                                @foreach($dataExaminations as $key =>
                                $examinations)
                                @if($examinations->trangthai_kythi == 1)
                                <option value="{{$examinations->id}}">
                                    {{$examinations->ten_kythi}} - Đang mở
                                </option>
                                @endif @endforeach
                            </select>
                            <input
                                type="hidden"
                                name="typeRead"
                                id="typeRead"
                                value="{{ $typeRead }}"
                            />
                        </div>
                    </div>
                </div>
                <div class="card">
                    <p class="card-header" style="margin-top: 10px">
                        Đọc Nhanh
                    </p>
                    <form
                        class="card-body"
                        action="/"
                        method="GET"
                        id="createVoiceFromIdRegistration"
                        onsubmit="readRightAway(event)"
                    >
                        <div class="list-course-row">
                            <label for="">Nhập số báo danh</label>
                            <input
                                type="number"
                                name="idRegistration"
                                id="idRegistration"
                                class="form-input"
                            />
                            <button class="bg-green color-white">
                                Xác Nhận
                            </button>
                        </div>
                    </form>
                </div>
                <audio controls id="studentVoice" style="display: none">
                    <source src="" type="audio/mp3" />
                </audio>
                <audio controls id="typeReadVoice" style="display: none">
                    <source src="" type="audio/mp3" />
                </audio>
            </div>
            <div class="list-right">
                <div class="card card-student">
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
                <div class="card" style="margin-top: 20px">
                    <p class="card-header" style="height: 40px">
                        Danh Sách Học Viên {{ $typeRead == "lythuyet" ? "Lý Thuyết" : "Mô
                        Phỏng" }}
                    </p>
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
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('js/read.js') }}"></script>
    </body>
</html>
