$.ajax({
    url: supplierSales,
    type: 'get',
    dataType: 'json',
    success: function (data) {
        const supplierChart = document.getElementById("supplierChart").getContext("2d");

        new Chart(supplierChart, {
            type: "doughnut",
            data: {
                labels: ["Esla Holidays", "Chologhuri", "Take Off", "Khun Habibi"],
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
        })
    },
    error: function (e) {
        console.log(e);
        alert('Error happened!');
    }
});
