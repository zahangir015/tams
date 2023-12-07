$.ajax({
    url: salesDue,
    type: 'get',
    dataType: 'json',
    success: function (data) {
        const object = {
            sales: [20, 25, 30, 40, 50, 55, 60, 65, 20, 30, 45, 80],
            due: [10, 20, 30, 40, 50, 10, 20, 30, 40, 50, 20, 45],
            profitLoss: [20, 25, -30, -40, -50, 55, 60, 65, -20, 30, 45, -80],
        };

        const ctx = document.getElementById("salesDueChart").getContext("2d");

        // Convert Profit/Loss values to positive for display
        const displayValues = object.profitLoss.map((value) => Math.abs(value));

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ],
                datasets: [
                    {
                        label: "Sales",
                        data: object.sales,
                        backgroundColor: "#337ABE",
                    },
                    {
                        label: "Due",
                        data: object.due,
                        backgroundColor: "#E1A917",
                    },
                    {
                        label: "",
                        data: displayValues,
                        backgroundColor: object.profitLoss.map((value) =>
                            value < 0 ? "#CC4236" : "#3F9777"
                        ),
                    },
                ],
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                    y: {
                        display: false,
                        beginAtZero: true,
                        grid: {
                            display: false,
                        },
                    },
                },
                plugins: {
                    customCanvasBackgroundColor: {
                        color: "lightGreen",
                    },
                    legend: {
                        display: false, // Set this to false to hide the legend
                    },
                },
                barPercentage: 1, // Adjust this value to reduce the gap between bars
                categoryPercentage: 0.7,
            },
        })
    },
    error: function (e) {
        console.log(e);
        alert('Error happened!');
    }
});
