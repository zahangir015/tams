const salesSourceChart = document
  .getElementById("salesSourceChart")
  .getContext("2d");

new Chart(salesSourceChart, {
  type: "doughnut",
  data: {
    labels: ["New Clients", "Existing Clients", "Online Clients"],
    datasets: [
      {
        label: "",
        data: [40, 40, 20],
        backgroundColor: ["#337ABE", "#3F9777", "#CC4236"],
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
