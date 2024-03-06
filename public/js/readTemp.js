var dataReadTemp = [];
var idExaminations = getParameterByName("idExaminations");
var codeExaminations = "";
var isEndedHandled = false;
document.addEventListener("DOMContentLoaded", loadData());
var csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}
function loadData() {
    url = "/read-temp/get-data?idExaminations=" + idExaminations;
    const options = {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
    };
    fetch(url, options)
        .then((response) => response.json())
        .then((data) => {
            dataReadTemp = data.dataReadTemp;
            codeExaminations = data.codeExaminations;
            renderTable(data.dataReadTemp);
            voice1();
        })
        .catch(() => console.error(error));
}
function renderTable(data) {
    studentTable = "";
    data.forEach((item, i) => {
        studentTable += `<tr class="bg-table">
                    <td>${i + 1}</td>
                    <td>${item["sobaodanh_hocvien"]}</td>
                    <td style="text-align:left">${item["hoten_hocvien"]}</td>
                    <td style="text-align:left">${
                        i == 0 ? "Đang gọi tên" : "Sắp đến lượt"
                    }</td>
                </tr>`;
    });
    studentTable =
        `<tr style="height:30px">
<th style="position:sticky; top:0;background-color:#f1d96e">STT</th>
<th style="position:sticky; top:0;background-color:#f1d96e">Số báo danh</th>
<th style="position:sticky; top:0;background-color:#f1d96e">Tên học viên</th>
<th style="position:sticky; top:0;background-color:#f1d96e">Trạng thái</th>
</tr>` + studentTable;
    document.getElementById("student-table").innerHTML = studentTable;
}
function updateDataReadTemp(idReadTemp) {
    const url = "/read-temp/update-data";
    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ idReadTemp: idReadTemp }),
    };
    fetch(url, options)
        .then((response) => response.json())
        .then((data) => {
            console.log(data.message);
        })
        .catch((error) => {
            console.error(error);
        });
}
function handleEnded() {
    if (!isEndedHandled) {
        isEndedHandled = true;
        voice2();
    }
}

function voice1() {
    updateInfoStudent(0);
    var voice1Audio = document.getElementById("voice1Audio");
    console.log(dataReadTemp);
    console.log(
        "audios/examinations/" +
            codeExaminations +
            "/" +
            dataReadTemp[0]["voice_hocvien"]
    );
    voice1Audio.src =
        "audios/examinations/" +
        codeExaminations +
        "/" +
        dataReadTemp[0]["voice_hocvien"];
    voice1Audio.type = "audio/mp3";
    voice1Audio.autoplay = true;
    isEndedHandled = false;
    voice1Audio.addEventListener("ended", handleEnded);
}

function voice2() {
    var voice2Audio = document.getElementById("voice2Audio");
    voice2Audio.src = "audios/other/" + dataReadTemp[0]["voice_loaidoc"];
    voice2Audio.type = "audio/mp3";
    voice2Audio.autoplay = true;
    voice2Audio.addEventListener("ended", function () {
        updateDataReadTemp(dataReadTemp[0]["id"]);
        window.location.reload();
    });
    isEndedHandled = false;
}
function updateInfoStudent(i) {
    var typeReadText = " lý thuyết ";
    if (dataReadTemp[i]["voice_loaidoc"] == "mophong.mp3") {
        typeReadText = " mô phỏng.";
    }
    var studentVoiceText =
        "Mời " +
        "<span class='student-voice-text-hightlight'>" +
        dataReadTemp[i]["hoten_hocvien"] +
        "</span>" +
        " có số báo danh " +
        "<span class='student-voice-text-hightlight'>" +
        dataReadTemp[i]["sobaodanh_hocvien"] +
        "</span>" +
        " vào phòng thi" +
        "<span class='student-voice-text-hightlight'>" +
        typeReadText.toUpperCase() +
        "</span>";

    document.getElementById("student-voice-text").innerHTML = studentVoiceText;
    console.log(dataReadTemp);
}
setInterval(() => window.location.reload(), 10000);
