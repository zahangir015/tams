$.ajax({
  url: sourceSales,
  type: 'get',
  dataType: 'json',
  success: function (data) {
    const salesSourceChart = document
        .getElementById("salesSourceChart")
        .getContext("2d");
    new Chart(salesSourceChart, {
      type: "doughnut",
      data: {
        labels: data.saleSource.labels, //["New Clients", "Existing Clients", "Online Clients"],
        datasets: [
          {
            label: "",
            data: data.saleSource.percentage, //[40, 40, 20]
            backgroundColor: data.saleSource.colorCodes, //["#337ABE", "#3F9777", "#CC4236"],
            hoverOffset: 4,
          },
        ],
      },
      options: {
        plugins: {
          legend: {
            display: true,
          },
        },
      },
    });
  },
  error: function (e) {
    console.log(e);
    alert('Error happened!');
  }
});

