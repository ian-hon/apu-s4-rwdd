const ctx = document.getElementById('linechart').getContext('2d');

<<<<<<< Updated upstream
const greenGradient = ctx.createLinearGradient(0, 0, 0, 400);
greenGradient.addColorStop(0, 'rgba(107, 179, 13, 0.4)'); 
greenGradient.addColorStop(1, 'rgba(107, 179, 13, 0)');   
=======
// 1. Create Gradients for the fill
const greenGradient = ctx.createLinearGradient(0, 0, 0, 400);
greenGradient.addColorStop(0, 'rgba(107, 179, 13, 0.4)'); // Top
greenGradient.addColorStop(1, 'rgba(107, 179, 13, 0)');   // Bottom
>>>>>>> Stashed changes

const yellowGradient = ctx.createLinearGradient(0, 0, 0, 400);
yellowGradient.addColorStop(0, 'rgba(255, 165, 0, 0.3)');
yellowGradient.addColorStop(1, 'rgba(255, 165, 0, 0)');

<<<<<<< Updated upstream

=======
// 2. Chart Configuration
>>>>>>> Stashed changes
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Nov 27', 'Nov 28', 'Nov 29', 'Nov 30', 'Dec 01', 'Dec 02', 'Dec 03'],
        datasets: [{
            label: 'Completed',
            data: [45, 52, 61, 58, 75, 85, 95],
            borderColor: '#6bb30d',
            backgroundColor: greenGradient,
            fill: true,
<<<<<<< Updated upstream
            tension: 0.4,
            pointRadius: 0,
=======
            tension: 0.4, // This creates the smooth curve
            pointRadius: 0, // Hides points like in your image
>>>>>>> Stashed changes
            borderWidth: 2
        }, {
            label: 'Pending',
            data: [12, 15, 10, 18, 14, 11, 8],
            borderColor: '#ffa500',
            backgroundColor: yellowGradient,
            fill: true,
            tension: 0.4,
            pointRadius: 0,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRario:false,
            plugins: {
                title: {
                    display: true,
                    text: 'Task Completion Trend',
                    color: '#fff'
                }, 
                legend: {
                    position: 'bottom',
                    labels: {
                    color: '#fff',
                    usePointStyle: true,
                    padding: 20
                }
                }
            },
            scales: {
                x: {
<<<<<<< Updated upstream
                    grid: { display: false }, 
=======
                    grid: { display: false }, // Hides vertical lines
>>>>>>> Stashed changes
                    ticks: { color: '#555' }
                },
                y: {
                    beginAtZero: true,
<<<<<<< Updated upstream
                    grid: { color: 'rgba(255, 255, 255, 0.05)' }, 
=======
                    grid: { color: 'rgba(255, 255, 255, 0.05)' }, // Subtle horizontal lines
>>>>>>> Stashed changes
                    ticks: { color: '#555', stepSize: 25 }
                }
            }
    }
});


const ctx2 = document.getElementById('roundchart').getContext('2d');
    
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Completed', 'In Progress', 'Pending', 'Failed'],
            datasets: [{
                data: [68, 16, 14, 3],
                backgroundColor: [
<<<<<<< Updated upstream
                    '#2ecc71', 
                    '#3498db', 
                    '#f1c40f', 
                    '#e74c3c'  
                ],
                borderWidth: 1,
                borderColor: '#0a0a05' 
=======
                    '#2ecc71', // Green
                    '#3498db', // Blue
                    '#f1c40f', // Yellow/Gold
                    '#e74c3c'  // Red
                ],
                borderWidth: 1,
                borderColor: '#0a0a05' // Matches container background
>>>>>>> Stashed changes
            }]
        },
        options: {
            responsive: true,
            maintainAspectRario:false,
            plugins: {
                title: {
                    display: true,
                    text: 'Task Status Distribution',
                    color: '#fff'
                }, 
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
<<<<<<< Updated upstream
                        color: '#fff', 
=======
                        color: '#fff', // White text for legend
>>>>>>> Stashed changes
                        usePointStyle: true,
                        padding: 20
                    }
                },
                tooltip: {
                    callbacks: {
                        label: (context) => ` ${context.label}: ${context.raw}%`
                    }
                }
            }
        }
    });

const ctx3 = document.getElementById('activechart').getContext('2d');

<<<<<<< Updated upstream
   
=======
    // Data points approximated from the image
>>>>>>> Stashed changes
const labels = ['Nov 27', 'Nov 28', 'Nov 29', 'Nov 30', 'Dec 01', 'Dec 02', 'Dec 03'];
const activeUsersData = [28, 32, 35, 30, 38, 42, 45];
const newUsersData = [3, 5, 2, 4, 6, 3, 7];
new Chart(ctx3, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Active Users',
                data: activeUsersData,
<<<<<<< Updated upstream
                borderColor: '#5cb85c', 
                backgroundColor: '#5cb85c',
                tension: 0.4,
=======
                borderColor: '#5cb85c', // Green
                backgroundColor: '#5cb85c',
                tension: 0.4, // Creates the smooth curve
>>>>>>> Stashed changes
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2
            },
            {
                label: 'New Users',
                data: newUsersData,
<<<<<<< Updated upstream
                borderColor: '#8e44ad', 
=======
                borderColor: '#8e44ad', // Purple
>>>>>>> Stashed changes
                backgroundColor: '#8e44ad',
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRario:false,
        plugins: {
            title: {
                display: true,
                text: 'User Engagement Over Time',
                color: '#fff'
                }, 
            legend: {
                position: 'bottom',
                labels: {
                    color: '#888',
                    usePointStyle: true,
                    pointStyle: 'circle',
                    padding: 20
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 60,
                ticks: {
                    stepSize: 15,
                    color: '#666'
                },
                grid: {
                    color: '#1a1a1a', 
                    drawBorder: false
                }
            },
            x: {
                ticks: {
                    color: '#666'
                },
                grid: {
                    display: false 
                }
            }
        }
    }
})