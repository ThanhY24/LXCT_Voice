<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Home</title>
  </head>
  <body>
    <div class="main">
      <div class="list-left">
        @if(session('message'))
            <div class="msg">{{ session('message') }}</div>
        @endif
        <div class="card">
          <p class="card-header">Lọc Học Viên Theo Khóa</p>
          <form class="card-body" action="/" methtod="GET">
            <div class="list-course-row">
              <label for="">Chọn khóa thi</label>
              <select name="idExaminations" id="">
                @foreach($dataExaminations as $key => $examinations)
                    @if($examinations->trangthai_kythi == 1)
                      <option value="{{$examinations->id}}">{{$examinations->ten_kythi}} - Đang mở</option>
                    @else
                      <option value="{{$examinations->id}}">{{$examinations->ten_kythi}} - Đã kết thúc</option>
                    @endif
                @endforeach
              </select>
              <button class="bg-green color-white">Xác Nhận</button>
            </div>
          </form>
        </div>
        <div class="card" style="margin-top: 20px">
          <p class="card-header">Nhập File Khóa Thi</p>
          <form class="card-body" method="POST" action="import-from-xml" enctype="multipart/form-data">
            <div class="student-form">
              <label for="">Chọn file khóa học</label>
              @csrf
              <input type="file" name="XMLFile" />
              <div class="card-checkbox">
                <input type="checkbox" id="print-inv" name="isPrint">
                <label for="print-inv">In kèm phiếu thu cho kỳ thi</label>
              </div>
              <button class="bg-green color-white">Xác Nhận</button>
            </div>
          </form>
        </div>
      </div>
      <div class="list-right">
        <div class="card">
          <p class="card-header" style="height:40px">Danh Sách Học Viên</p>
          <div class="card-body" style="max-height: 630px; overflow: auto; padding: 0; margin:0">
            <table class="table1" >
              <tr style="height:30px" class="bg-table">
                <th style="position:sticky; top:0; background-color: #f1d96e;">STT</th>
                <th style="position:sticky; top:0; background-color: #f1d96e;">Số báo danh</th>
                <th style="position:sticky; top:0; background-color: #f1d96e;">Số giấy tờ</th>
                <th style="position:sticky; top:0; background-color: #f1d96e;">Ngày sinh</th>
                <th style="position:sticky; top:0; background-color: #f1d96e;">Tên học viên</th>
                <th style="position:sticky; top:0; background-color: #f1d96e;">KQ Lý thuyết</th>
                <th style="position:sticky; top:0; background-color: #f1d96e;">KQ Mô phỏng</th>
              </tr>
              @foreach($dataStudent as $key => $student)
              <tr class="bg-table">
                <td>{{$key + 1}}</td>
                <td>{{$student["sobaodanh_hocvien"]}}</td>
                <td style="text-align: left">{{$student["sogiayto_hocvien"]}}</td>
                <td>{{ \Carbon\Carbon::parse($student["ngaysinh_hocvien"])->format('d/m/Y') }}</td>
                <td style="text-align:left">{{$student["hoten_hocvien"]}}</td>
                <td>{{$student["ketqualythuyet_hocvien"] == null ? "Chưa thi" : $student["ketqualythuyet_hocvien"]}}</td>
                <td>{{$student["ketquamophong_hocvien"] == null ? "Chưa thi" : $student["ketquamophong_hocvien"]}}</td>
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
