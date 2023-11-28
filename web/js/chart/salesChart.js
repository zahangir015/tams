const salesChart = document.getElementById("salesChart").getContext("2d");

new Chart(salesChart, {
  type: "doughnut",
  data: {
    labels: ["Holiday", "Air Ticket", "Hotel", "Visa"],
    datasets: [
      {
        label: "",
        data: [40, 30, 20, 10],
        backgroundColor: ["#337ABE", "#3F9777", "#CC4236", "#E1A917"],
        hoverOffset: 4,
      },
    ],
  },
  options: {
    plugins: {
      legend: {
        display: false,
      },
    },
  },
});
