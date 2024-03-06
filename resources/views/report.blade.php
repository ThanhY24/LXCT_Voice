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
    <title>Danh sách hóa đơn</title>
  </head>
  <body>
    <div class="main">
      <div class="list-left">
        @if(session('message'))
            <div class="msg">{{ session('message') }}</div>
        @endif
        <div id="msg" class="msg"></div>
        <div class="card">
          <p class="card-header">Lọc biên lai</p>
          <form class="card-body">
            <div class="list-course-row">
              <label for="">Chọn khóa thi</label>
              @csrf
              <select name="idExaminations" id="idExaminations">
                @foreach($dataExaminations as $key => $examinations)
                  <option value="{{$examinations->id}}" {{$idExaminations == $examinations->id ? "selected" : ""}}>{{$examinations->ten_kythi}} - {{ $examinations->trangthai_kythi == 1 ? "Đang mở" : "Đã đóng" }}</option>
                @endforeach
              </select>
              <button class="color-white" style="background-color: #2FC582" name="exportExcel">Xuất Excel</button>
              <button class="bg-green color-white">Xác Nhận</button>
            </div>
          </form>
        </div>
      </div>
      <div class="list-right">
        <div class="card">
          <p class="card-header" style="height:40px" id="titleListReadImport">Danh Sách Biên Lai Đã Thu Tiền</p>
          <div class="card-body" style="max-height: 630px; overflow: auto; padding: 0; margin:0">
            <table class="table1" id="list-read-table">
              <tr style="height:30px" class="bg-table">
                <th style="position:sticky; top:0;" class="bg-main">STT</th>
                <th style="position:sticky; top:0;" class="bg-main">Số báo danh</th>
                <th style="position:sticky; top:0;" class="bg-main">Tên học viên</th>
                <th style="position:sticky; top:0;" class="bg-main">LT</th>
                <th style="position:sticky; top:0;" class="bg-main">MP</th>
                <th style="position:sticky; top:0;" class="bg-main">TH</th>
                <th style="position:sticky; top:0;" class="bg-main">ĐT</th>
                <th style="position:sticky; top:0;" class="bg-main">Tổng tiền</th>
                <th style="position:sticky; top:0;" class="bg-main">Ngày thu</th>
              </tr>
              @foreach($dataInv as $key =>$inv)
              <tr style="height:30px" class="bg-table">
                <td style="position:sticky; top:0;">{{$key+1}}</td>
                <td style="position:sticky; top:0;">{{$inv["hocVien"]["sobaodanh_hocvien"]}}</td>
                <td style="position: sticky; top: 0; text-align: left;">{{$inv["hocVien"]["hoten_hocvien"]}}</td>
                <td style="position:sticky; top:0;">{{number_format($inv["sotienthu_bienlai"])}} đ</td>
                <td style="position:sticky; top:0;"></td>
                <td style="position:sticky; top:0;"></td>
                <td style="position:sticky; top:0;"></td>
                <td style="position:sticky; top:0;">{{number_format($inv["sotienthu_bienlai"])}} đ</td>
                <td style="position:sticky; top:0;">{{formatDate($inv["created_at"])}}</td>
              </tr>
              @endforeach
            </table>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
