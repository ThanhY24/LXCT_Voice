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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <title>Nhập dữ liệu</title>
  </head>
  <body>
    <div class="main">
      <div class="list-left">
        @if(session('message'))
            <div class="msg">{{ session('message') }}</div>
        @endif
        <div id="msg" class="msg"></div>
        <div class="card">
          <p class="card-header">Nhập dữ liệu đọc</p>
          <form class="card-body" action="/read/import?typeRead={{$typeRead}}" id="importExamForm" method="POST">
            <div class="list-course-row">
              <label for="">Chọn khóa thi</label>
              @csrf
              <select name="idExaminations" id="idExaminations">
                @foreach($dataExaminations as $key => $examinations)
                    @if($examinations->trangthai_kythi == 1)
                      <option value="{{$examinations->id}}">{{$examinations->ten_kythi}} - Đang mở</option>
                    @endif
                @endforeach
              </select>
              <label for="" style="margin-top: 10px">Nhập số báo danh học viên</label>
              <input type="text" name="registrationStudent" id="registrationStudent" class="form-input" required autofocus>
              <div class="radio-custom-container">
                <input type="radio" name="typeRead" id="lythuyet" value="lythuyet" checked>
                <label for="lythuyet" onClick="loadDataRead()">
                  <span>Lý thuyết</span>
                </label>
                <input type="radio" name="typeRead" id="mophong" value="mophong">
                <label for="mophong" onClick="loadDataRead()">
                  <span>Mô phỏng</span>
                </label>
              </div>
              <div class="card-checkbox">
                <input type="checkbox" id="print-inv" name="print-inv" {{$isPrintINV == 1 ? "checked" : ""}}>
                <label for="print-inv">In phiếu thu</label>
              </div>
              <div class="card-checkbox">
                <input type="checkbox" id="priority" name="priority">
                <label for="priority">Ưu tiên</label>
              </div>
              <button class="bg-green color-white" >Xác Nhận</button>
            </div>
          </form>
        </div>
      </div>
      <div class="list-right">
        <div class="card">
          <p class="card-header" style="height:40px" id="titleListReadImport"></p>
          <div class="card-body" style="max-height: 630px; overflow: auto; padding: 0; margin:0">
            <table class="table1" id="list-read-table">
              <tr style="height:30px" class="bg-table">
                <th style="position:sticky; top:0;">STT</th>
                <th style="position:sticky; top:0;">Tên học viên</th>
                <th style="position:sticky; top:0;">Số báo danh</th>
                <th style="position:sticky; top:0;">Số giấy tờ</th>
                <th style="position:sticky; top:0;">Ưu tiên</th>
              </tr>
              @foreach($dataRead as $key => $read)
              <tr class="bg-table">
                <td>{{$read["stt_doc"]}}</td>
                <td style="text-align:left">{{$read["hocVien"]["hoten_hocvien"]}}</td>
                <td>{{$read["hocVien"]["sobaodanh_hocvien"]}}</td>
                <td>{{$read["duongdan_doc"] != null ? $read["duongdan_doc"] : "Chưa tạo"}}</td>
                <td>{{$read["uutien_doc"] != null ? "Có" : "Không"}}</td>
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ asset('js/import.js') }}"></script>
  </body>
</html>
