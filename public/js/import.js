var dataConfig = [];
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
// Lấy config để thêm vào biên lai
function getConfig() {
    const url = "/configuration";
    const options = {
        method: "GET",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
    };
    fetch(url, options)
        .then((response) => response.json())
        .then((data) => {
            dataConfig = data.dataConfig;
        })
        .catch((error) => console.error(error));
}
getConfig();
// Tải dữ liệu danh sách học viên lần đầu
function loadDataRead() {
    typeRead = document.querySelector('input[name="typeRead"]:checked').value;
    if (typeRead == "lythuyet") {
        document.getElementById("titleListReadImport").innerHTML =
            "Danh Sách Học Viên Lý Thuyết";
    } else {
        document.getElementById("titleListReadImport").innerHTML =
            "Danh Sách Học Viên Mô Phỏng";
    }
    idExaminations = document.getElementById("idExaminations").value;
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
    const url = "/read/load-data-import";
    const data = {
        typeRead: typeRead,
        idExaminations: idExaminations,
    };
    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify(data),
    };
    fetch(url, options)
        .then((response) => response.json())
        .then((data) => {
            renderTable(data.dataRead);
        })
        .catch((error) => console.error(error));
}

loadDataRead();
// Sự kiện khi submit form nhập học viên vào để đọc
document
    .getElementById("importExamForm")
    .addEventListener("submit", (event) => {
        event.preventDefault();
        registrationStudent = document.getElementById(
            "registrationStudent"
        ).value;
        priority = document.getElementById("priority").checked ? 1 : 0;
        typeRead = document.querySelector(
            'input[name="typeRead"]:checked'
        ).value;
        idExaminations = document.getElementById("idExaminations").value;
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        const url = "/read/import";
        const data = {
            registrationStudent: registrationStudent,
            priority: priority,
            typeRead: typeRead,
            idExaminations: idExaminations,
        };
        const options = {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(data),
        };
        fetch(url, options)
            .then((response) => response.json())
            .then((data) => {
                if (data.type == "WARR") {
                    console.log(data.message);
                    loadDataRead();
                    alert(data.message);
                } else {
                    printInv();
                    loadDataRead();
                    alert(data.message);
                }
            })
            .catch((error) => console.error("Lỗi:", error));
    });
// Tạo bảng
function renderTable(data) {
    studentTable = "";
    data.forEach((item, i) => {
        studentTable += `<tr class="bg-table">
                        <td>${i + 1}</td>
                        <td style="text-align:left">${item.hoten_hocvien}</td>
                        <td>${item.sobaodanh_doc}</td>
                        <td style="text-align:left">${
                            item.hoc_vien.sogiayto_hocvien
                        }</td>
                        <td style="text-align:left">${
                            item.uutien_doc == 1 ? "Có" : "Không"
                        }</td>
                        <td style="text-align:left">${
                            item.danhap_hocvien == "1" ? "Đã đọc" : "Chưa đọc"
                        }</td>
                        <td style="text-align:left">
                        <p class="p_button" onClick="printInvAgain(${
                            item.ma_hocvien
                        })">In</p>
                        </td>
                    </tr>`;
    });
    studentTable =
        `<tr style="height:30px">
    <th style="position:sticky; top:0;background-color:#f1d96e">STT</th>
    <th style="position:sticky; top:0;background-color:#f1d96e">Tên học viên</th>
    <th style="position:sticky; top:0;background-color:#f1d96e">Số báo danh</th>
    <th style="position:sticky; top:0;background-color:#f1d96e">Số giấy tờ</th>
    <th style="position:sticky; top:0;background-color:#f1d96e">Ưu tiên</th>
    <th style="position:sticky; top:0;background-color:#f1d96e">Trạng thái</th>
    <th style="position:sticky; top:0;background-color:#f1d96e"></th>
    </tr>` + studentTable;
    document.getElementById("list-read-table").innerHTML = studentTable;
}
// INV
function printInv() {
    if (document.getElementById("print-inv").checked) {
        var idRegistration = document.getElementById(
            "registrationStudent"
        ).value;
        var idExaminations = document.getElementById("idExaminations").value;
        var checkboxes = document.getElementsByName("typeRead");
        var selectedValues = Array.from(checkboxes).filter(
            (checkbox) => checkbox.checked
        );
        if (idRegistration == "") {
            alert("Vui lòng nhập số báo danh");
        } else {
            const url = "/inv/create";
            const options = {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    idRegistration: idRegistration,
                    idExaminations: idExaminations,
                    typeRead: selectedValues[0].value,
                }),
            };
            fetch(url, options)
                .then((response) => response.json())
                .then((data) => {
                    dataInvoice = data.dataInvoice;
                    amountInWords = data.amountInWords;
                    var currentDate = new Date();
                    var currentDateStr =
                        "Ngày " +
                        addLeadingZero(currentDate.getDate()) +
                        " tháng " +
                        addLeadingZero(currentDate.getMonth() + 1) +
                        " năm " +
                        addLeadingZero(currentDate.getFullYear());
                    var printWindow = window.open("", "_blank");
                    printWindow.document.write(
                        "<html><head><title>Print</title>"
                    );
                    printWindow.document.write(
                        '<link rel="stylesheet" type="text/css" href="../css/print.css" media="print"></head><body>'
                    );

                    var printContents = `<div class="inv-container" id="inv-container">
                        <div class="inv-header">
                        <p class="inv-header-name">
                        ${dataConfig["name"]}
                        </p>
                        <p class="inv-header-address">${
                            dataConfig["address"]
                        }</p>
                        <p class="inv-header-line"></p>
                        <p class="inv-header-title-id">Số: ${
                            dataInvoice["id"]
                        }</p>
                        <p class="inv-header-title">BIÊN LAI THU TIỀN</p>
                        <p class="inv-header-date"><i>${currentDateStr}</i></p>
                        </div>
                        <div class="inv-body">
                        <p class="inv-body-item"><span>Kỳ thi: </span>${parseInt(
                            dataInvoice["ky_thi"]["ma_kythi"]
                        )
                            .toString()
                            .padStart(10, "0")}</p>

                        <p class="inv-body-item"><span>Ngày thi: </span>${convertDateFormat(
                            dataInvoice["created_at"]
                        )}</p>
                        <p class="inv-body-item">
                            <span>Số tiền: </span>${formatCurrency(
                                dataInvoice["sotienthu_bienlai"]
                            )}
                        </p>
                        <p class="inv-body-item">
                            <span></span><i>(${amountInWords})</i>
                        </p>
                        <p class="inv-body-item"><span>Học viên: </span>${
                            dataInvoice["hoc_vien"]["hoten_hocvien"]
                        }</p>
                        <div class="registration-container">
                            <p>SBD</p>
                            <p>${parseInt(
                                dataInvoice["hoc_vien"]["sobaodanh_hocvien"],
                                10
                            )}</p>
                        </div>
                        <p class="inv-body-note">
                            Học viên vui lòng giữ phiếu thu và đợi gọi tên vào phòng thi
                        </p>
                        </div>
                        <div class="inv-footer">
                        <p class="inv-footer-text">
                            Mọi thắc mắc, vui lòng liên hệ Hotline:  ${
                                dataConfig["hotline"]
                            }
                        </p>
                        </div>
                        <div class="qrCode-container">
                        <div id="qrcode"></div>
                        <div class="qrcode-content">
                            NGÂN HÀNG ĐẠI CHÚNG <br />
                            Tên: TT GDNN DT & SAT HACH LAI XE CHIEN THANG <br />
                            STK: 108000293246
                        </div>
                        </div>
                    </div>`;
                    printWindow.document.write(printContents);
                    printWindow.document.write("</body></html>");
                    printWindow.document.close();

                    printWindow.onload = function () {
                        var qrcodeContainer =
                            printWindow.document.getElementById("qrcode");
                        new QRCode(qrcodeContainer, {
                            text: dataConfig["QRCodeBank"],
                            width: 70,
                            height: 70,
                            correctLevel: QRCode.CorrectLevel.L,
                        });
                        printWindow.print();
                        printWindow.close();
                    };
                })
                .catch((error) => console.error(error));
        }
    }
}
function printInvAgain(idStudent) {
    var idExaminations = document.getElementById("idExaminations").value;
    var checkboxes = document.getElementsByName("typeRead");
    var selectedValues = Array.from(checkboxes).filter(
        (checkbox) => checkbox.checked
    );
    const url =
        "/inv/get?idStudent=" +
        idStudent +
        "&idExaminations=" +
        idExaminations +
        "&type=" +
        selectedValues[0].value;
    const options = {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
    };
    console.log(url);
    fetch(url, options)
        .then((response) => response.json())
        .then((data) => {
            dataInvoice = data.dataInvoice;
            amountInWords = data.amountInWords;
            var currentDate = new Date();
            var currentDateStr =
                "Ngày " +
                addLeadingZero(currentDate.getDate()) +
                " tháng " +
                addLeadingZero(currentDate.getMonth() + 1) +
                " năm " +
                addLeadingZero(currentDate.getFullYear());
            var printWindow = window.open("", "_blank");
            printWindow.document.write("<html><head><title>Print</title>");
            printWindow.document.write(
                '<link rel="stylesheet" type="text/css" href="../css/print.css" media="print"></head><body>'
            );

            var printContents = `<div class="inv-container" id="inv-container">
                        <div class="inv-header">
                        <p class="inv-header-name">
                        ${dataConfig["name"]}
                        </p>
                        <p class="inv-header-address">${
                            dataConfig["address"]
                        }</p>
                        <p class="inv-header-line"></p>
                        <p class="inv-header-title-id">Số: ${
                            dataInvoice["id"]
                        }</p>
                        <p class="inv-header-title">BIÊN LAI THU TIỀN</p>
                        <p class="inv-header-date"><i>${currentDateStr}</i></p>
                        </div>
                        <div class="inv-body">
                        <p class="inv-body-item"><span>Kỳ thi: </span>${parseInt(
                            dataInvoice["ky_thi"]["ma_kythi"]
                        )}</p>

                        <p class="inv-body-item"><span>Ngày thi: </span>${convertDateFormat(
                            dataInvoice["created_at"]
                        )}</p>
                        <p class="inv-body-item">
                            <span>Số tiền: </span>${formatCurrency(
                                dataInvoice["sotienthu_bienlai"]
                            )}
                        </p>
                        <p class="inv-body-item">
                            <span></span><i>(${amountInWords})</i>
                        </p>
                        <p class="inv-body-item"><span>Học viên: </span>${
                            dataInvoice["hoc_vien"]["hoten_hocvien"]
                        }</p>
                        <div class="registration-container">
                            <p>SBD</p>
                            <p>${parseInt(
                                dataInvoice["hoc_vien"]["sobaodanh_hocvien"],
                                10
                            )}</p>
                        </div>
                        <p class="inv-body-note">
                            Học viên vui lòng giữ phiếu thu và đợi gọi tên vào phòng thi
                        </p>
                        </div>
                        <div class="inv-footer">
                        <p class="inv-footer-text">
                            Mọi thắc mắc, vui lòng liên hệ Hotline:  ${
                                dataConfig["hotline"]
                            }
                        </p>
                        </div>
                        <div class="qrCode-container">
                        <div id="qrcode"></div>
                        <div class="qrcode-content">
                            NGÂN HÀNG ĐẠI CHÚNG <br />
                            Tên: TT GDNN DT & SAT HACH LAI XE CHIEN THANG <br />
                            STK: 108000293246
                        </div>
                        </div>
                    </div>`;
            printWindow.document.write(printContents);
            printWindow.document.write("</body></html>");
            printWindow.document.close();

            printWindow.onload = function () {
                var qrcodeContainer =
                    printWindow.document.getElementById("qrcode");
                new QRCode(qrcodeContainer, {
                    text: dataConfig["QRCodeBank"],
                    width: 70,
                    height: 70,
                    correctLevel: QRCode.CorrectLevel.L,
                });
                printWindow.print();
                printWindow.close();
            };
        })
        .catch((error) => console.error(error));
}
function convertDateFormat(originalDate) {
    var dateObject = new Date(originalDate);
    var day = addLeadingZero(dateObject.getDate());
    var month = addLeadingZero(dateObject.getMonth() + 1);
    var year = dateObject.getFullYear();
    var formattedDate = `${day}/${month}/${year}`;

    return formattedDate;
}
function addLeadingZero(number) {
    return number < 10 ? "0" + number : number;
}
function formatCurrency(amount) {
    var dinhDangTien = new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
    });
    var formattedAmount = dinhDangTien.format(amount);
    formattedAmount = formattedAmount.replace(/₫/g, "");

    return formattedAmount.trim();
}
