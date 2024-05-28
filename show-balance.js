/* Pie chart */

window.onload = function () {
  var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    title: {
      text: "",
    },
    data: [
      {
        type: "pie",
        startAngle: 240,
        yValueFormatString: '##0.00"PLN"',
        indexLabel: "{label} {y}",
        dataPoints: [
          { y: 50.45, label: "Food" },
          { y: 100.31, label: "House" },
          { y: 150.06, label: "Transport" },
          { y: 50.91, label: "Clothing" },
          { y: 10.26, label: "Hygiene" },
        ],
      },
    ],
  });
  chart.render();
};

/* Operation on dates */

function readDay() {
  const date = new Date();
  var day = date.getDate();

  if (day < 10) day = "0" + day;

  return day;
}

function readMonth() {
  const date = new Date();
  var month = date.getMonth() + 1;

  if (month < 10) month = "0" + month;

  return month;
}

function readYear() {
  const date = new Date();
  var year = date.getFullYear();

  return year;
}

function readTodayDate() {
  const date = new Date();

  var day = date.getDate();
  var month = date.getMonth() + 1;
  var year = date.getFullYear();

  if (month < 10) month = "0" + month;
  if (day < 10) day = "0" + day;

  var currentDate = `${year}.${month}.${day}`;

  return currentDate;
}

function readPreviousMonth() {
  var month = "";
  var monthsTable = [
    "01",
    "02",
    "03",
    "04",
    "05",
    "06",
    "07",
    "08",
    "09",
    "10",
    "11",
    "12",
  ];

  month = readMonth();

  for (var i = 0; i < 12; i++) {
    if (month == monthsTable[0]) {
      month = monthsTable[11];
      break;
    } else if (month == monthsTable[i]) {
      month = monthsTable[i - 1];
      break;
    }
  }
  return month;
}

function checkPreviousYear() {
  var month = 0,
    year = 0;
  month = readMonth();
  year = readYear();

  if (month == 1) year -= 1;

  return year;
}

function calculateHowManyDaysPerPreviousMonth() {
  var days = 0,
    month = 0,
    year = 0;
  month = readPreviousMonth();
  year = readYear();

  if (
    month == 1 ||
    month == 3 ||
    month == 5 ||
    month == 7 ||
    month == 8 ||
    month == 10 ||
    month == 12
  )
    days = 31;
  else if (month == 4 || month == 6 || month == 9 || month == 11) days = 30;
  else if (month == 2) {
    if ((year % 4 == 0 && year % 100 != 0) || year % 400 == 0) days = 29;
    else days = 28;
  }
  return days;
}

/* Drop-down button */

document.getElementById("currentMonth").addEventListener("click", function () {
  var beginningOfCurrentMonth = readYear() + "." + readMonth() + ".01";

  document.getElementById("changePeriod").innerHTML = "current month";
  document.getElementById("showDateRange").innerHTML =
    "(from " + beginningOfCurrentMonth + " to " + readTodayDate() + ")";
});

document.getElementById("previousMonth").addEventListener("click", function () {
  document.getElementById("changePeriod").innerHTML = "previous month";
  document.getElementById("showDateRange").innerHTML =
    "(from " +
    checkPreviousYear() +
    "." +
    readPreviousMonth() +
    ".01" +
    " to " +
    checkPreviousYear() +
    "." +
    readPreviousMonth() +
    "." +
    calculateHowManyDaysPerPreviousMonth() +
    ")";
});

document.getElementById("currentYear").addEventListener("click", function () {
  document.getElementById("changePeriod").innerHTML = "current year";
  document.getElementById("showDateRange").innerHTML =
    "(from " + readYear() + ".01.01" + " to " + readTodayDate() + ")";
});

document.getElementById("customPeriod").addEventListener("click", function () {
  document.getElementById("saveBtn").addEventListener("click", function () {
    var fromDate = 0,
      toDate = 0;

    document.getElementById("changePeriod").innerHTML = "custom period";
    fromDate = document.getElementById("balanceFromDate").value;
    toDate = document.getElementById("balanceToDate").value;

    document.getElementById("showDateRange").innerHTML =
      "(from " + fromDate + " to " + toDate + ")";
  });
});

/* Scroll to top button */

const btn = document.getElementById("scrollToTop");

btn.addEventListener("click", () =>
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  })
);
