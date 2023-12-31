$.ajax({
    url: serviceSales,
    type: 'get',
    dataType: 'json',
    success: function (data) {
        console.log(data)
        const salesChart = document.getElementById("salesChart").getContext("2d");
        new Chart(salesChart, {
            type: "doughnut",
            data: {
                labels: ["Holiday", "Air Ticket", "Hotel", "Visa"],
                datasets: [
                    {
                        label: "",
                        data: data,
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
        console.log(e.getMessage());
        alert('Error happened sales report!');
    }
});
