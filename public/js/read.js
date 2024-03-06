var index = 0;
var isEndedHandled = false;
var dataRead = [];
var codeExam = "";
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
// Hàm load dữ liệu mới
function loadData() {
    const typeRead = document.getElementById("typeRead").value;
    const idExaminations = document.getElementById("idExaminations").value;
    const url = "/read";
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
            dataRead = data.dataRead;
            codeExam = data.codeExaminations;
            renderTable(data.dataRead);
        })
        .catch((error) => console.error(error));
}
function updateData(id) {
    const url = "/read/update";
    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ dataRead: dataRead, idStudent: id }),
    };
    fetch(url, options)
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("msg").innerHTML = data.message;
        })
        .catch((error) => console.error(error));
}
// Thêm vào hàng đợi
function addToStack(data) {
    const url = "/read-temp";
    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ dataRead: data }),
    };
    fetch(url, options)
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("msg").innerHTML = data.message;
        })
        .catch((error) => console.error(error));
}
// Load trang
document.addEventListener("DOMContentLoaded", loadData());
function keyPress(event) {
    if (event.key === "Enter") {
        if (index < dataRead.length) {
            event.preventDefault();
            updateInfoStudent(index);
            updateData(moveElementToEndAndUpdateIndex(dataRead));
            addToStack(dataRead[dataRead.length - 1]);
            console.log(dataRead[dataRead.length - 1]);
            loadData();
            renderTable(dataRead);
        } else {
            alert("Đã đọc hết tên học viên");
        }
    }
}

function renderTable(data) {
    studentTable = "";
    data.forEach((item, i) => {
        studentTable += `<tr class="bg-table">
                    <td>${i + 1}</td>
                    <td>${item["sobaodanh_doc"]}</td>
                    <td style="text-align:left">${item["hoten_hocvien"]}</td>
                    <td style="text-align:left">${
                        item["uutien_doc"] == "1" ? "Có" : "Không"
                    }</td>
                </tr>`;
    });
    studentTable =
        `<tr style="height:30px">
<th style="position:sticky; top:0;background-color:#f1d96e">STT</th>
<th style="position:sticky; top:0;background-color:#f1d96e">Số báo danh</th>
<th style="position:sticky; top:0;background-color:#f1d96e">Tên học viên</th>
<th style="position:sticky; top:0;background-color:#f1d96e">Ưu tiên</th>
</tr>` + studentTable;
    document.getElementById("student-table").innerHTML = studentTable;
}
function updateInfoStudent(i) {
    var typeReadText = " lý thuyết ";
    if (dataRead[i]["ma_loaidoc"] == 2) {
        typeReadText = " mô phỏng.";
    }
    var studentVoiceText =
        "Mời " +
        "<span class='student-voice-text-hightlight'>" +
        dataRead[i]["hoten_hocvien"] +
        "</span>" +
        " có số báo danh " +
        "<span class='student-voice-text-hightlight'>" +
        dataRead[i]["sobaodanh_doc"] +
        "</span>" +
        " vào phòng thi" +
        "<span class='student-voice-text-hightlight'>" +
        typeReadText.toUpperCase() +
        "</span>";

    document.getElementById("student-voice-text").innerHTML = studentVoiceText;
}
function moveElementToEndAndUpdateIndex(data) {
    const firstElement = data.shift();
    data.push(firstElement);
    updateIndexInData(data);
    return data[data.length - 1]["id"];
}
function updateIndexInData(data) {
    for (let i = 0; i < data.length; i++) {
        data[i]["stt_doc"] = i + 1;
    }
}

function readRightAway(event) {
    event.preventDefault();
    typeRead = getParameterByName("typeRead");
    idRegistration = document.getElementById("idRegistration").value;
    idExaminations = getParameterByName("idExaminations");
    if (idRegistration == undefined) {
        alert("Vui lòng nhập số báo danh");
    } else {
        const url = "/read-temp/add-to-top";
        const options = {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                idRegistration: idRegistration,
                idExaminations: idExaminations,
                typeRead: typeRead,
            }),
        };
        fetch(url, options)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("msg").innerHTML = data.message;
            })
            .catch((error) => console.error(error));
    }
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
