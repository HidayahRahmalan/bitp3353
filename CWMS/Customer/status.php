<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Washing Progress</title>
    <style>
        .progress-bar {
            width: 100%;
            background-color: #f3f3f3;
        }
        .progress {
            width: 0%;
            height: 30px;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
        }
    </style>
</head>
<body>

<h2>Washing Progress</h2>
<div id="progress-bar-container" class="progress-bar">
    <div id="progress" class="progress">0%</div>
</div>

<script>
    // Replace car_id with the actual car ID you're tracking
    const carId = 1;

    function updateProgress() {
        fetch(`get_progress.php?car_id=${carId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const progressElement = document.getElementById('progress');
                    progressElement.style.width = data.progress + '%';
                    progressElement.textContent = data.progress + '%';

                    if (data.progress >= 100) {
                        clearInterval(progressInterval);
                    }
                } else {
                    console.error('Failed to fetch progress');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Update progress every 5 seconds
    const progressInterval = setInterval(updateProgress, 5000);
</script>

</body>
</html>
